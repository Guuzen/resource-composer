## Overview
This is a library which goal is to simplify arrays (**resources** in terms of library) joins from different data sources (databases, APIs, etc).

There is two resource types in context of one relationship:

**related resource** - will be joined to main resource.

**main resource** - resource to which related resource will be joined.

Inspired by https://gist.github.com/fesor/2e1b7cea1b60aa764a9d0da7b7ea2a1d

## How to use it
For example - you have **main resource**, let's call it User, which is stored in document database.
When loaded from database it will look like this: 
```php
$user = [
    'id' => '1',
    'is_active' => true,
];
```

And **related resource**, let's call it UserInfo, which is stored in relational database.
It will look like this:
```php
$userInfo = [
    'user_id' => '1',
    'fullname' => 'John Doe',
];
```
Obviously it is not possible to easy join this **resources** from different databases by standard SQL join, but it is possible to do this join on application side (so-called application side joins).

This is just an example, and it is easy to assign UserInfo to User, but in real world scenarios there will be list of users with multiple related type of **resources** which will have nested **resources**, and you will have to write cycles in cycles and recursive functions every single time.

To join User with UserInfo you need to:

1. Describe **main resource**
```php
final class User extends DefaultMainResource
{
    protected function config(): void
    {
        $this->hasOne(
            resource: UserInfo::class, // see below
            joinBy: 'id', // join will be performed by this field of User
            joinTo: 'user_info', // join will be be made to this field of User
        );
    }
}
```

2. Describe **related resource**
```php
final class UserInfo implements RelatedResource
{
    private ResourceLoader $resourceLoader;

    public function __construct(ResourceLoader $resourceLoader)
    {
        $this->resourceLoader = $resourceLoader;
    }

    public function loader(): ResourceLoader
    {
        return $this->resourceLoader;
    }

    public function resource(): string
    {
        return self::class;
    }
}
```

3. Write loader for **related resource** (Doctrine DBAL example)
```php
final class UserInfoLoader implements ResourceLoader
{
    private Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function load(array $userIds): array
    {
        $userInfos = $this->db->fetchAllAssociative(
            'select user_id, fullname from user_info where user_info.user_id in (:user_ids)',
            ['user_ids' => $userIds],
            ['user_ids' => Connection::PARAM_STR_ARRAY],
        );

        return $userInfos;
    }
        
    public function loadBy(): string
    {
        return 'user_id';
    }
}
```

4. Register all resources in ResourceComposer instance
```php
$composer = new ResourceComposer();
$composer->registerMainResource(new User())
$composer->registerRelatedResource(
    new UserInfo(
        new UserInfoLoader($connection)
    )
);
```
5. Execute compose or composeList with one or list of Users
```php

$userWithInfo = $composer->composeOne($user, User::class);
```
$userWithInfo will contain:
```php
[
    'id' => '1',
    'is_active' => true,
    'user_info' => [
        'user_id' => '1',
        'fullname' => 'John Doe',
]
```
