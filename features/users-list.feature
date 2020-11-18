Feature: List of users
    In order to see the list of users
    As a some user
    I ask the page pf the list of users

    Scenario: I want the list of users from the login page
        Given I am on the login page 
        When I request the list of users from "/users"
        Then I see the page with the title "Liste des utilisateurs"
