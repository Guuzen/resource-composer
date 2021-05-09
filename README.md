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

This is just an example and it is easy to assign UserInfo to User, but in real world scenarios there will be list of users with multiple related type of **resources** which will have nested **resources** and you will have to write cycles in cycles and recursive functions every single time.

To join User with UserInfo you need to:

1. Describe **main resource** by name and [collector](#promise-collectors).
2. Choose how User and UserInfo related to each other.
3. Describe **related resource** by name, key by wich UserInfo will be joined and [loader](#loader).
4. Call `composeOne` (Because we have only one user. In case of list call `compose`) with **main resource** and its name.
```php
$composer = new ResourceComposer();
$composer->registerRelation(
    new MainResource(
        'User', // name of the main resource to join.
        new SimpleCollector(
            'id', // field in User by which join will took place
            'user_info', // field in User to which related resource will be written
        ),
    OneToOne(), // User has one UserInfo
    new RelatedResource(
        'UserInfo', // name of the related resource to join.
        'user_id', // UserInfo will be joined by user_id
        new UserInfoLoader($connection), // loader for UserInfo
    ),
);
$userWithInfo = $composer->composeOne($user, 'User');
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
## Loader

Loader is implementation of fetching **related resource** from data storage. It can be database, api etc.

Example implementation for first example and DBAL:

```php
final class UserInfoLoader implements ResourceDataLoader
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
}
```

## Promise collectors

Promise collectors collect promises for every **main resource**. Promises allow to defer **related resources** loading and assigning **related resources** to **main resources** for preformance reasons.
You can control how ids from **main resource** will be collected and how assigning **related resources** will be done.

### Simple collector
Lets see code of `SimpleCollector` from example. It implements `PromiseCollector` interface and return array of promises from its single method.
```php

final class SimpleCollector implements PromiseCollector
{
    private $readKey;

    private $writeKey;

    public function __construct(string $readKey, string $writeKey)
    {
        $this->readKey  = $readKey;
        $this->writeKey = $writeKey;
    }

    public function collect(\ArrayObject $resource): array
    {
        return [
            new Promise(
                function (\ArrayObject $resource): string|int|null {
                    return $resource[$this->readKey] ?? null; // this is how id from main resource will be collected
                },
                function (\ArrayObject $resource, mixed $writeValue): void {
                    $resource[$this->writeKey] = $writeValue; // this is how related resource will be written to main resource
                },
                $resource
            )
        ];
    }
}
```

### Array collector
Use case is when **main resource** contains array of ids by which you want to made join.

For example join for Customer that has array of Orders
```php
$customer = [
    'id' => 'nonsense',
    'orders' => ['1', '2'],
];

$orders = [
    ['id' => '1', 'price' => 100],
    ['id' => '2', 'price' => 200],
];
```
can be configured like this:
```php
$composer = new ResourceComposer();
$composer->registerRelation(
    new MainResource(
        'Customer',
        new ArrayCollector('orders', 'orders'),
    ),
    OneToOne(),
    new RelatedResource(
        'Order', 
        'id', 
        new OrderLoader($connection),
    ),
);
$customerWithOrders = $composer->composeOne($customer, 'Customer');
```
and result will be:
```php
[
    'id' => 'nonsense',
    'orders' => [
        ['id' => '1', 'price' => 100],
        ['id' => '2', 'price' => 200],
    ],
]
```

### Merge collector
Use case is when there is need to merge array of collectors in order to of compose multiple joins with **related resource** of same type but write it to a different fields in **main resource** and do not do request to storage for every field.

For example Application has Files of one type but in different fields.
```php
$application = [
    'id' => 'nonsense',
    'fileA' => 'typeA',
    'fileB' => 'typeB',
];

$fileA = [
    'id' => 'typeA',
    'path' => 'some path to A',
];
$fileB = [
    'id' => 'typeB',
    'path' => 'some path to B',
];
```
can be configured like this:
```php
$composer = new ResourceComposer();
$composer->registerRelation(
    new MainResource(
        'Application', 
        new MergeCollector([
            new SimpleCollector('fileA', 'fileA'),
            new SimpleCollector('fileB', 'fileB'),
        ]),
    ),
    OneToOne(),
    new RelatedResource('File', 'id', new FileLoader($connection)),    
);
$applicationWithFiles = $composer->composeOne($application, 'Application');
```
and result will be:
```php
[
    'id' => 'nonsense',
    'fileA' => [
        'id' => 'typeA',
        'path' => 'some path to A',
    ],
    'fileB' => [
        'id' => 'typeB',
        'path' => 'some path to B',
    ],
]
```

### Custom collector
Just gives option to do whatever you want inline.
