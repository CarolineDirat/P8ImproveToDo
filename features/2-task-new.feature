Feature: Creation of a new task
    In order to create a new task
    As an authenticated user
    I fill in the creation form and validate it

    Background:
        Given I am authenticated user as "user1" with "password"
    
    Scenario: I create a task with good data
        Given I am on "/tasks/create"
        When I fill in the following
                | task[title]    | Titre de la nouvelle tâche   |
                | task[content]  | Contenu de la nouvelle tâche |
        And I press "Ajouter"
        And I follow redirect
        Then I see the alert message ".alert.alert-success"
        And I see the page with the title "Liste des tâches"
