Hello dear API creator!

This repository is a playground for your submission.

Before getting started, please hit the `Use this template button` to create a new repository on which you commit and push your code regularly for your task. Once you are done, please mail us the link to your repository.

If you encounter a problem or have questions about the task, feel free to email us under christian.schaefers@vero.de

Good luck and have fun ☘️

## Prerequisites:
The already built up code frame in this repo is a very basic API with limited functionality. Your task is to pick it up and develop new features on top of it.

You can change existing code structure however you can't add any external frameworks and third party classes.

There is an SQLite database (`testDb.db`) which is created and filled on the fly.

There is a basic routing in `index.php` which supports `GET` and `POST` calls in particular:
- `GET constructionStages`
- `GET constructionStages/{id}`
- `POST constructionStages`

The API serves data and accepts payload only in JSON format.

## Task 1:
Add a new API call `PATCH constructionStages/{id}` which to allow the API users to edit an arbitrary field of a selected (by id) construction stage. The API should touch only the fields which are sent by the user. Add validation which to ensure that if `status` field is sent it is either `NEW`, `PLANNED` or `DELETED` and throw a proper error if it is not.

Add another `DELETE constructionStages/{id}` API call which changes the `status` of the selected resource to `DELETED`.

_The patch function is [here](https://github.com/SiavashBamshadnia/vero-api-tasks/blob/main/classes/ConstructionStages.php#L98)._

_The delete function is [here](https://github.com/SiavashBamshadnia/vero-api-tasks/blob/main/classes/ConstructionStages.php#L159)._

## Task 2:
Write a validation system which checks every posted field against a set of rules as follows:
- `name` is maximum of 255 characters in length
- `start_date` is a valid date&time in iso8601 format i.e. `2022-12-31T14:59:00Z`
- `end_date` is either `null` or a valid datetime which is later than the `start_date`
- `duration` is skipped because it should be automatically calculated based on `start_date`, `end_date` and `durationUnit`
- `durationUnit` is one of `HOURS`, `DAYS`, `WEEKS` or can be skipped (which fallbacks to default value of `DAYS`)
- `color` is either `null` or a valid HEX color i.e. `#FF0000`
- `externalId` is `null` or any string up to 255 characters in length
- `status` is one of `NEW`, `PLANNED` or `DELETED` and the default value is `NEW`.

You should throw proper errors if a rule is not met.

_Validators are [here](https://github.com/SiavashBamshadnia/vero-api-tasks/tree/main/classes/validation)._

[This](https://github.com/SiavashBamshadnia/vero-api-tasks/blob/main/classes/exceptions/ValidationException.php) is their exception class that is just a simple extended class.

_And I handled the way of returning exception responses in the [index.php](https://github.com/SiavashBamshadnia/vero-api-tasks/blob/main/index.php) file._

## Task 3:
Set a logic which automatically calculates `duration` based on `start_date`, `end_date` and `durationUnit` as you know that:
- `start_date` is required and is a valid date&time in iso8601 format i.e. `2022-12-31T14:59:00Z`
- `end_date` is either `null` (then `duration` is also `null`) or a valid datetime which is later than the `start_date`
- `durationUnit` is one of `HOURS`, `DAYS`, `WEEKS` where `DAYS` is the default fallback.
- `duration` is a positive float value calculated in precision of whole hours (ignore minutes and seconds if any)
- a week has 7 days and one day has 24 hours

_getDuration function is [here](https://github.com/SiavashBamshadnia/vero-api-tasks/blob/main/classes/ConstructionStagesCreate.php#L34)._

## Default task:
Add a nice phpDoc to every method you create!

_I did it. :)_

## Bonus task:
Add a system which generates a documentation out of your API!

_[Here](https://github.com/SiavashBamshadnia/vero-api-tasks/blob/main/classes/ApiDocumentation.php) it is. It's like a mini-swagger :)_