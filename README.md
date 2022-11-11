<p align="center">
  <a href="https://www.needfor-school.com/">
      <picture>
        <source media="(prefers-color-scheme: dark)" srcset="https://www.needfor-school.com/wp-content/uploads/2021/09/logo_Need_For_School.jpg">
        <img src="https://www.needfor-school.com/wp-content/uploads/2021/09/logo_Need_For_School.jpg" width="128">
      </picture>
    <h1 align="center">
      KikooLoL Server
    </h1>
  </a>
</p>

<p align="center">
  <a aria-label="onRuntime Studio" href="https://onruntime.com" target="_blank">
    <img src="https://img.shields.io/badge/MADE%20BY%20ONRUNTIME-fff.svg?style=for-the-badge&labelColor=000">
  </a>
  <a aria-label="License" href="https://github.com/needforschool/kikoolol-server/blob/master/LICENSE" target="_blank">
    <img alt="" src="https://img.shields.io/npm/l/next.svg?style=for-the-badge&labelColor=000000">
  </a>
</p>

## Description

[KikooLoL](https://github.com/needforschool/kikoolol-server) is a school project made for [Need For School](https://www.needfor-school.com/) by the [onRuntime Studio](https://onruntime.com)'s team.

This project aims to create a gaming platform where League of Legends players can see their statistics and compare them with their friends.
So they can improve their skills and become the best.

>To see the documentation, start the server and go to the /docs page, there is a Swagger documentation that register every endpoints of this API.

## Getting Started

### Prerequisites

For this project we installed:

	* Symfony 5.4
	* Apache 2.4.41
	* PHP >= 8.x.x
	* MongoDB Server


### Implementation Details

2) Using bundles: 	

       * FOSRestBundle
       * JMSSerializerBundle
       * NelmiCorsBundle

### Installation

1. Clone the repo

```sh
git clone https://github.com/needforschool/kikoolol-server
```

2. Install Composer packages

```sh
composer install
```

3. Create a .env.local file and add your riot api key

```sh
RIOT_API_KEY=RGAPI-xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
```

Then go to .env and set your database informations (only MongoDB)

```sh
DATABASE_URL=database_url
MONGODB_DB=database_name
```

3. Initialize the database

```sh
php bin/console doctrine:database:create
```

```sh
php bin/console doctrine:schema:update --force
```

4. Run the server

```sh
symfony server:start
```

## Contributing

Please see our [contributing rules](https://docs.onruntime.com/contributing/introduction).

## Authors

- Antoine Kingue ([@antoinekm](https://github.com/antoinekm))
- Jérémy Baudrin ([@jerembdn](https://github.com/jerembdn))

## License

KikooLoL Server is [MIT licensed](LICENSE).