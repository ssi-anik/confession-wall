## Graphql Queries from the frontend using playground

### Named or anonymous/shorthand queries

```graphql endpoint doc
query hello_query {
  hello
}

query {
  custom_resolver
}

{
  custom_resolver_with_method
}

{
  greet(name: "Anik")
}

query this_is_called_operation_name($name: String!) {
  greet(name: $name)
}

query get_greet_with_optional_age ($name: String!) {
  greet_with_optional_age(name: $name)
}

query get_greet_with_optional_age ($name: String!, $age: Int) {
  greet_with_optional_age(name: $name, age: $age)
}

query greet_as_default {
  alias_name: greet_with_default_value
}

# Don't pass variable, otherwise the default value will be overwritten
query ($name : String = "Default value on client side") {
  greet_with_default_value(name: $name)
}

```

### VARIABLES
```json 
{
  "name": "Syed Sirajul Islam Anik",
  "age": 28
}
```

---

### User login
```graphql endpoint doc
mutation ($input: LoginInput!) {
  login (input: $input) {
    __typename
    ... on LoginPayload {
      access_token
      type
      expires_in
    }

    ... on Error {
      message
    }
  }
}
```

### VARIABLES

- change the `username` or `password` to check the error.
```json
{
  "input": {
    "username": "confession-wall",
    "password": "12345"
  }
}
```

---

### Create user account
```graphql endpoint doc
mutation ($input: CreateAccountInput!) {
  createAccount(input: $input) {
    __typename
    ... on Message {
      message
    }
  }
}
```

### VARIABLES

```json
{
  "input": {
    "name": "new confession wall name",
    "username": "confession-wall-1",
    "password": "12345",
    "password_confirmation": "12345",
    "email": "confession.wall+1@confession-wall.com"
  }
}
```