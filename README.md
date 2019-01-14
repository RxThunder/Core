<p align="center"><img src="./resources/thunder-logo.svg"></p>

<p align="center">
<a href="https://packagist.org/packages/rxthunder/core"><img src="https://poser.pugx.org/rxthunder/core/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/rxthunder/core"><img src="https://poser.pugx.org/rxthunder/core/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/rxthunder/core"><img src="https://poser.pugx.org/rxthunder/core/license.svg" alt="License"></a>
</p>
<p align="center">
<a href="https://travis-ci.org/RxThunder/Core"><img src="https://travis-ci.org/RxThunder/Core.svg?branch=master" alt="Build"></a>
<p align="center">


## About Thunder

This repository is the core library of the Thunder CLI micro framework.

The aim is to provide a simple extensible kernel with DI.

## Todos
- [X] Add Eventstore persistent subcription console
- [X] Add RabbitMq console
- [X] Add JSON schema validation of messages (part of thunder-model lib)
- [X] Add Eventstore projection creation command
- [X] Add Eventstore persistent subscription creation command
- [x] Add RabbitMQ queue/binding creation command
- [ ] Add Eventstore transient projections consumption
- [ ] Add Eventstore stream consumption
- [ ] Add Eventstore specific event consumption
- [ ] Make AbstractSubject optional in the router
- [ ] Implement an extension/plugin system
- [ ] Extract EventStore code into a plugin
- [ ] Extract RabbitMQ code into a plugin
- [ ] Add CRON console
- [ ] Extract CRON console code into a plugin
