# User stats

## Installation
```
composer require fluffy-factory/user-stats-bundle
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

Make a database schema update

```
php bin/console d:s:u --force
```

### Routing

Create routes file `config/routes/fluffy_user_stats.yaml` with the below config:

```yaml
fluffy_user_stats:
  resource: "@UserStatsBundle/Controller/UserStatsController.php"
  type: annotation
  # prefix: /admin
```

## Integration

### Easyadmin

Add custom actions in your entity configuration

```php
$userStats = Action::new('userStats', 'Statistiques utilisateur')
    ->linkToRoute('fluffy_user_stats', function (User $entity) {
       return [
           'id' => $entity->getId()
       ];
    });

return parent::configureActions($actions)
    ->add(Crud::PAGE_INDEX, $userStats)
        ->setPermission('userStats', 'ROLE_SUPER_ADMIN');
```