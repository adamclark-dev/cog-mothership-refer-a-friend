# Refer a Friend

The `Message\Mothership\ReferAFriend` cogule allows users to refer non-users to the site, and potentially receive a reward for doing so.

## Referrals

The `Referral` object represents an instance where a user has referred someone to the site. The user can refer as many people as they like, but any email address may only be referred once. If a user attempts to refer someone who has already been referred, they will get an error message informing them. They can also only refer non-users.

The referral is made up of the following information:

+ **Referrer** - The user who made the referral
+ **Referred email address** - The email address for the person who has been referred
+ **Referred name** - The name of the person who has been referred
+ **Reward config** - The configuration for the reward that will be granted upon a successful referral
+ **Status** - The status of the referral (**pending**, **complete**, **error** or **expired**)
+ **Created at** - The time the referral was made

## Rewards

### Reward types
Rewards can come in several types.
Only one reward type is packaged with this module: `no_reward`. This type acts as a simple referral but the referrer does not get anything when the referral is marked as complete. It is marked as complete when the user registers with the site and logs in.

### Reward configuration
Each reward type can be configured in its own way, using a set of **Entities**, which are stored against a `Config` class.
There are three types of entity that can be stored against a configuration:

+ Triggers
+ Constraints
+ Reward options

Each of these types have an associated collection which extends the `Message\Cog\ValueObject\Collection` class, and all entities are registered in the service container within an instance of their associated collection.

The `Message\Mothership\ReferAFriend\Reward\Type\TypeInterface` has three methods: `validTriggers()`; `validConstraints()`; and `validRewardOptions()`, to determine which entities apply to a specific reward type. These return one dimensional arrays of the names of these entities (returned by the entities' `getName()` methods).

#### Triggers
Triggers determine which event must be fired for a referral to be potentially marked as complete.

They must implement `Message\Mothership\ReferAFriend\Reward\Config\Trigger\TriggerInterface`.

The Referral object has a `hasTriggered()` method to quickly determine whether it has triggered. It takes the event name as its argument.

In order for a trigger to be effective, there must be an event listener listening out for the same event that loads the reward. This can then load the referral using a the email address, e.g.:

```
public function createReward(SomeEvent $event)
{
    $referrals = $this->get('refer.referral.loader')->getByEmail($event->getEmail());

    // Return if no referrals were loaded
    if (empty($referrals)) {
        return;
    }

    foreach ($referrals as $referral) {
        // Continue if trigger does not apply to event
        if (false === $referral->hasTriggered('event.name')) {
            continue;
        }

        // code to create reward
    }
}
```

**Note:** The referral loader returns an array. All methods on the loader return an array of referrals, with the exception of `getByID()`. This is so that controllers know what to expect when calling the loader, and for future proofing if the module is ever changed to allow multiple referrals to be made to one address.

Triggers must be added by extending the `refer.reward.config.triggers` service in the service container, and adding them via the `add()` method on the collection:


```
$services['refer.reward.config.triggers'] = $services->extend('refer.reward.config.triggers', function($triggers, $c) {
    $triggers->add(new My\New\Trigger);

    return $triggers;
});
```

#### Constraints
Constraints determine a rule that must be fulfilled for a referral to be marked as complete and a reward to be generated. This is stored in the database as a simple key/value pair.

They must implement `Message\Mothership\ReferAFriend\Reward\Config\Constraint\ConstraintInterface`.
The constraint must be associated with a <a href="http://symfony.com/doc/current/book/forms.html#built-in-field-types">Symfony form type</a>. This is returned using the `getFormType()` method on the constraint. Any options that should be put against this form field should be returned as an array by the `getFormOptions()` method on the constraint.

Constraints also have an `isValid()` method which take the referral as its first argument, and an instance of `Message\Cog\Event\Event`, which was fired to trigger the validation of the referral as its second parameter.

So to extend the example above, we can check the validity of the referral by looping through its constraints:

```
public function createReward(SomeEvent $event)
{
    $referrals = $this->get('refer.referral.loader')->getByEmail($event->getEmail());

    // Return if no referrals were loaded
    if (empty($referrals)) {
        return;
    }

    foreach ($referrals as $referral) {
        // Continue if trigger does not apply to event
        if (false === $referral->hasTriggered('event.name')) {
            continue;
        }

        // Default to $valid being true, loop through constraints and validate
        $valid = true;
        foreach ($referral->getRewardConfig()->getConstraints() as $constraint) {
            // Break out of loop if referral is not valid, and set $valid to false
            if (false === $constraint->isValid($$referral, $event)) {
                $valid = false;
                break;
            }
        }

        // Move on to next referral if invalid
        if (false === $valid) {
            continue;
        }

        // code to create reward
    }
}
```

**Note:** While the `ReferralInterface` has a method of `hasTriggered()` to quickly access the triggers and check to see if it has been triggered, there is no `isValid()` method. The reason for this is that there may be circumstances where you may want to ignore a constraint under a certain circumstance.

Constraints must be added by extending the `refer.reward.config.constraints` service in the service container, and calling the `add()` method on the collection:

```
$services['refer.reward.config.constraints'] = $services->extend('refer.reward.config.constraints', function($constraints, $c) {
    $constraints->add(new My\New\Constraint);

    return $constraints;
});
```

#### Reward options
Reward options are used to configure the reward, if any, that will be generated upon the successful completion of the referral.
They must implement `Message\Mothership\ReferAFriend\Reward\Config\RewardOption\RewardOptionInterface`.

Like constraints, they are stored as a key/value pair, and they must be associated with a <a href="http://symfony.com/doc/current/book/forms.html#built-in-field-types">Symfony form type</a>, and these are defined using the `getFormType()` and `getFormOptions()` methods.

Reward options must be added by extending the `refer.reward.config.reward_options` service in the service container, and calling the `add()` method on the collection:

```
$services['refer.reward.config.reward_options'] = $services->extend('refer.reward.config.reward_options', function($rewardOptions, $c) {
    $rewardOptions->add(new My\New\RewardOption);

    return $rewardOptions;
});
```

## Proxies

This module uses lazy loading on both the referrals and the reward configurations, and hence classes for `Message\Mothership\ReferAFriend\Referral\ReferralProxy` and `Message\Mothership\ReferAFriend\Reward\Config\ConfigProxy` exist.
These classes hold the loaders for their entities, as well as any IDs that they may need for loading, rather than the entities themselves. They extend the getters to call the loaders to load them entities from the database and set them against themselves as and when they need them. Once they have been loaded once, they will retrieve them from memory instead of the database for the rest of the request. For example, in the `ConfigProxy`:

```
/**
 * {@inheritDoc}
 */
public function getConstraints()
{
    if (null === $this->_constraints) {
        $constraints = $this->_loaders->get('constraint')->load($this);

        if (!$constraints) {
            throw new \LogicException('Could not load constraints!');
        }

        foreach ($constraints as $constraint) {
            $this->addConstraint($constraint);
        }
    }

    return parent::getConstraints();
}
```