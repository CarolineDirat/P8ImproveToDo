Feature: Edition of a new task
    In order to edit a new task
    As an authenticated user
    I fill in the edition form and validate it

    Background:
        Given I am authenticated user as "user1" with "password"
    
    Scenario: I edit a task with good data
        Given I am on "/tasks/1/edit"
        When I fill in the following
                | task[title]    | Titre de la tâche n°1 modifié   |
                | task[content]  | Contenu de la tâche n°1 modifié |
        And I press "Modifier"
        And I follow redirect
        Then I see the alert message ".alert.alert-success"
        And I see the page with the title "Liste des tâches"
