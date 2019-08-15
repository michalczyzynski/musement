## Description

This is an app that can generate sitemap using Musement API.

Structurally code is split into 3 parts:
- `app` - current application,
- `vendor` - external libraries that I use,
- `lib` - components/sdks that could be easily moved to external repositories and added to projects as vendors; kept
separate with this in mind.

There are 2 CLIs:
- `bin/symfony` is a CLI with all default symfony commands registered; usefull for some maintenance jobs,
- `bin/cli` is the app-specific CLI, exposing only the custom commands that come with this app.

## Installation

```bash
git clone git@github.com:michalczyzynski/musement-task.git <project dir>
composer install
vim .env # update 2 last vars
```

And you're set to go.

## 

## Application usage
Application commands are available in `bin/cli`.

### Generating sitemap:
```bash
bin/cli dump-sitemap <locale>
```

Valid locale options are:
- `it`,
- `fr`,
- `es`.

### Sending sitemap in email
```bash
bin/cli email-sitemap <locale> <email-1> <email-2> <...>
```

Valid locale options are:
- `it`,
- `fr`,
- `es`.

Emails: email addresses to send this sitemap to.

By default sending emails is disabled, but for test purposes you can enable integration with gmail fairly easily:
```bash
vim .env

# Update variable
MAILER_URL"gmail://<username>:<password>@localhost"
```

## Tests
Run `composer tests`. This will run unit tests for all parts of system + couple of integration tests.

No linters / cs fixers added - but this way you can see how my code actually looks unfiltered. Hope you don't mind.
