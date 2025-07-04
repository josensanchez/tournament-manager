# Tournament Manager

This project implements a tournament management system using state machines 
to define and control the lifecycle of key entities: Tournaments, Players, and Match Games. 


## Purpose

The core purpose of this application is to facilitate the creation, management, and progression of tournaments. 
It leverages a sophisticated state machine architecture to precisely track the status of:

*   **Tournaments:** From initial creation through registration, readiness, and active play.
*   **Players:** Managing their journey from registration to playing, elimination, or ultimately, winning the tournament.
*   **Match Games:** Overseeing individual matches from pending status to in-progress and final completion.

This interconnected system ensures that all components evolve coherently, providing a clear and controlled environment for tournament operations.

## Installation

To set up the project locally, follow these steps:

1.  **Environment Configuration:**
    Copy the example environment file and configure your settings:
    ```bash
    cp .env.example .env
    ```
    Edit the `.env` file to set up your database connection and other environment variables.

2.  **Install Laravel Sail:**
    If you don't have Laravel Sail installed globally, you can install it via Composer:
    ```bash
    composer install
    ./vendor/bin/sail up -d
    ./vendor/bin/sail artisan migrate --seed
    ```

3.  **Install Pre-commit Hooks:**
    Run the following command to install the necessary Git pre-commit hooks, which help maintain code quality:
    ```bash
    php artisan install:pre-commit
    ```

## Architecture

This project's architecture heavily relies on the **State Machine pattern**, 
The main reason for this is that all entities states relies on the state of at least one or more other entities.

Is a player is eliminated depends on the MatchGames and the evolution of the MatchGames changes the Tournament status.

to see more abount this state machine you can look at [StateMachine](./StateMachine.md)

The main benefit from this approach is that the entire system is divided in small discrete components (States and Transitions)

Adding new features will be adding or removing states or adding or removing transitions. 

In that way every new feature does not change the behaivor of the previous ones(there are some limitation on that of course). But follow the whole journey of an 
Entity is easy and stragth forward and every step of the way can be re tried if necesary.



