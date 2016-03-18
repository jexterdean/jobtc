#Tom's Project Manangement App

### Requirements

- php5.5 +
- mysql 5.6 +
- Laravel 5.1
    - Packages
      - (Authorization)[https://github.com/romanbican/roles]
      - (Imaging)[]

### Jex- Update 3/18/2016

Encountered these errors when ran migration(php artisan migrate --seed)

 [Illuminate\Database\QueryException]
  SQLSTATE[HY000]: General error: 1215 Cannot add foreign key constraint (SQL
  : alter table `fp_role_user` add constraint role_user_user_id_foreign forei
  gn key (`user_id`) references `fp_users` (`id`) on delete cascade)


  [PDOException]
  SQLSTATE[HY000]: General error: 1215 Cannot add foreign key constraint

