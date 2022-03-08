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
final class CommentHasAuthor implements ResourceLink
{
    /**
     * Loader implementation (which must make actual calls to storage) 
     */
    public function loaderClass(): string
    {
        return AuthorLoader::class;
    }

    /**
     * Extract values from all $comment->id
     * Load linked authors by $author->commentId
     * Write linked authors to respective $comment->author
     */
    public function resolver(): ResourceResolver
    {
        return new OneToOne('id', 'commentId', 'author');
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
$links = [new CommentHasAuthor(new Storage())];
$composer = ResourceComposer::create($links, new AuthorLoader());
$composer->loadRelated($comments);
```

every Comment will contain related Post
