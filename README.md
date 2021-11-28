## Overview
This is a library which goal is to simplify object (**resources** in terms of library) joins from different data sources (databases, APIs, etc).

Inspired by https://gist.github.com/fesor/2e1b7cea1b60aa764a9d0da7b7ea2a1d

## How to use it
For example - you have Comment, which is stored in document database:
```php
final class Comment
{
    public Author $author;

    public function __construct(
        public string $id,
    )
    {
    }
}

```

And Author, which is stored in relational database:
```php
final class Author
{
    public function __construct(
        public string $id,
        public string $commentId,
    )
    {
    }
}

```
Obviously it is not possible to easy join this **resources** from different databases by standard SQL join, but it is possible to do this join on application side (so-called application side joins).

To join Comment with Author you need to write resolver:

```php
/**
 * @implements ResourceResolver<Comment, Author>
 */
final class CommentHasAuthorResolver implements ResourceResolver
{
    public function __construct(private Storage $storage, private OneToOne $oneToOne)
    {
    }

    // extract all ids from Comment for join Comment with Author
    public function extractIds(object $resource): \Traversable
    {
        yield $resource->id;
    }

    // load from storage by extracted ids
    public function load(array $ids): array
    {
        return $this->storage->loadByIds($ids);
    }

    // group and assign loaded resources to Comment
    public function resolve(object $resource, array $loadedResources): void
    {
        $grouped = $this->oneToOne->group($loadedResources, fn(Author $author) => $author->commentId);

        $resource->author = $grouped[$resource->id];
    }

    // specify for which resource this resolver is
    public function resourceClass(): string
    {
        return Comment::class;
    }
}

```


Initialize ResourceComposer instance and load related resources
```php
$resolvers = [new CommentHasAuthorResolver(new Storage(), new OneToOne())];
$composer = ResourceComposer::create($resolvers);
$composer->loadRelated($comments);
```

every Comment will contain related Post
