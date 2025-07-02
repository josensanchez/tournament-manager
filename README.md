# Tournament Manager

This project implements a tournament management system using state machines to define and control the lifecycle of key entities: Tournaments, Players, and Match Games. This approach ensures a robust and predictable flow for managing complex tournament dynamics.

## Purpose

The core purpose of this application is to facilitate the creation, management, and progression of tournaments. It leverages a sophisticated state machine architecture to precisely track the status of:

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

This project's architecture heavily relies on the **State Machine pattern**, which is a central feature offering significant benefits:

*   **Clarity and Predictability:** Each entity (Tournament, Player, MatchGame) has clearly defined states and transitions, making the system's behavior easy to understand and predict. This reduces the likelihood of unexpected states or invalid operations.
*   **Robustness:** By enforcing valid transitions, the state machine prevents illegal state changes, leading to a more stable and error-resistant application.
*   **Maintainability:** The logic for state transitions is encapsulated within the state machine, separating it from the core business logic. This modularity makes the codebase easier to maintain, debug, and extend.
*   **Scalability:** The well-defined state transitions allow for easier integration of new features or changes to existing workflows without impacting unrelated parts of the system.
*   **Event-Driven Flow:** State changes can trigger specific actions or events, enabling a reactive and dynamic system where different parts of the application respond appropriately to changes in entity status.

This architectural choice provides a solid foundation for managing the complex, sequential nature of tournament progression.