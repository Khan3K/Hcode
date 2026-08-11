# Contributing

Thanks for taking the time to contribute to H-Code!

## Getting started

- Fork the repository and clone your fork.
- Create a feature branch: `git checkout -b my-feature`.
- Make your changes and verify them with a local PHP server:
  `php -S localhost:8080`.
- Commit with a clear, conventional message
  (`feat:`, `fix:`, `docs:`, `chore:`, `refactor:`).
- Open a pull request against `master` and describe what and why.

## Code style

- PHP 8.1+, PSR-12 where practical.
- 4-space indentation, LF line endings.
- No new external dependencies without discussing it first.

## Testing

There is no automated test suite yet. If your change touches behaviour,
include a short manual test description in the PR.
