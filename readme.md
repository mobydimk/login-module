# Installation

- Create config file **.env** using **.env.example**

| Key(s) | Value |
| --- | --- |
| APP_URL | App base URL |
| DB_ | MySQL connection credentials |
| DB2_ | Old login module MySQL connection credentials |
| MAIL_ | Mail driver config |
| FACEBOOK_ | FB oAuth credentials |
| GOOGLE_ | Google oAuth credentials |
| PMS_ | PMS oAuth credentials |
| AUTH_MASTER_HASH_MD5 | admin master password MD5 hash |

- Run **install.sh**

# Maintenance

- Data migration from old login module DB to new login module DB

``` php artisan lm:sync ```