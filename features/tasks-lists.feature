Feature: List of tasks
    In order to see a list of tasks
    As an authenticated user
    I ask a page of a list of tasks

    Background:
        Given I am authenticated user as "user1" with "password"

    Scenario Outline:
        And I am on "<page>"
        When I follow "<link>"
        Then I see the page with the title "<title>"
    
        Examples:
        | page                | link                                         | title                            |
        | /                   | Consulter la liste de toutes les tâches      | Liste des tâches                 |
        | /                   | Consulter la liste des tâches terminées      | Liste des tâches terminées       |
        | /                   | Consulter la liste des tâches à faire        | Liste des tâches non terminées   |
        | /tasks/create       | Consulter la liste de toutes les tâches      | Liste des tâches                 |
        | /tasks/create       | Consulter la liste des tâches terminées      | Liste des tâches terminées       |
        | /tasks/create       | Consulter la liste des tâches à faire        | Liste des tâches non terminées   |
        | /tasks/51/edit      | Consulter la liste de toutes les tâches      | Liste des tâches                 |
        | /tasks/51/edit      | Consulter la liste des tâches terminées      | Liste des tâches terminées       |
        | /tasks/51/edit      | Consulter la liste des tâches à faire        | Liste des tâches non terminées   |
        | /tasks              | Consulter la liste des tâches terminées      | Liste des tâches terminées       |
        | /tasks              | Consulter la liste des tâches à faire        | Liste des tâches non terminées   |
        | /tasks/filter/true  | Consulter la liste de toutes les tâches      | Liste des tâches                 |
        | /tasks/filter/true  | Consulter la liste des tâches à faire        | Liste des tâches non terminées   |
        | /tasks/filter/false | Consulter la liste de toutes les tâches      | Liste des tâches                 |
        | /tasks/filter/false | Consulter la liste des tâches terminées      | Liste des tâches terminées       |
