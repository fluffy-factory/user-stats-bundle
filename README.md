# User stats

## Installation
```
composer require fluffy-factory/user-stats
```

Make a database schema update

```
php bin/console d:s:u --force
```

## Configuration

### Entity

Add mixin Class `use UserStats` in your User entity
```php
class User
{
    ### ADD THIS ###
    use UserStats;

    ...
}
```

Make a data

### Routing

Create routes file `config/routes/fluffy_user_stats.yaml` with the below config:

```yaml
fluffy_user_stats:
  resource: "@UserStatsBundle/Controller/UserStatsController.php"
  type: annotation
  # prefix: /admin
```