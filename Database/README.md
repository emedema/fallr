# Working with Cassandra

## Handling multiple tables
Since Cassandra is NoSQL we cannot assume consistancy accross tables (we may have the same value in n tables). 
Therefore, we need to use `batch` statements which are precreated queries that run accross multiple tables. 
An example of a batch statement is:

```
BEGIN BATCH
  INSERT INTO userpage_posts (username, datestamp) VALUES ('user1', '2020-01-01');
  INSERT INTO subscribed_feed (username, datestamp) VALUES ('user1', '2020-01-01');
APPLY BATCH;
```

We can use this to ensure all insertions, updates, and deletes are applied consistantly accross all tables. This has
a cost for insertion, but as Cassandra provides decreased insertion time costs we don't need to worry about this now.

To read more about BATCH you can read the [DataStax Documentation](https://docs.datastax.com/en/archived/cql/3.1/cql/cql_reference/batch_r.html).