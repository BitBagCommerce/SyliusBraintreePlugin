@managing_braintree_payment_method
Feature: Braintree payment method validation
    In order to avoid making mistakes when managing a payment method
    As an Administrator
    I want to be prevented from adding it without specifying required fields

    Background:
        Given the store operates on a channel named "Web-RUB" in "RUB" currency
        And the store has a payment method "Offline" with a code "offline"
        And I am logged in as an administrator

    @ui
    Scenario: Trying to add a new braintree payment method without specifying required configuration
        Given I want to create a new Braintree payment method
        When I name it "Braintree" in "English (United States)"
        And I add it
        Then I should be notified that "Merchant ID" fields cannot be blank
        And I should be notified that "Public Key" fields cannot be blank
        And I should be notified that "Private Key" fields cannot be blank
