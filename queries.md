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

---

### Refresh token

```graphql endpoint doc
mutation {
  refreshToken {
    access_token
  }
}
```

### Header

```json
{
  "Authorization": "Bearer TOKEN_FROM_LOGIN"
}
```

---

### User Setting

```graphql endpoint doc
{
  userSetting {
    name
    email
    username
    profile_picture
    message_privacy
  }
}
```

### Header

```json
{
  "Authorization": "Bearer TOKEN_FROM_LOGIN"
}
```

---

### Change user setting

```graphql endpoint doc
mutation ($input: UserSettingInput!) {
  updateSetting (input: $input) {
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
    "current_password": "12345",
    "new_password": "12345",
    "new_password_confirmation": "12345",
    "message_privacy": "MSG_FROM_ANONYMOUS",
    "email": "new.email@mailer.com"
  }
}
```

### Header

```json
{
  "Authorization": "Bearer TOKEN_FROM_LOGIN"
}
```

---

### Update/Remove profile picture/avatar

- Remove profile picture

```graphql endpoint doc
mutation avatar($input: AvatarInput!) {
  avatar (input: $input) {
    __typename
    ... on Message {
      message
    }
  }
}
```
### Variable

```json
{
  "input": {
    "remove_profile_picture": true
  }
}
```

### Header

```json
{
  "Authorization": "Bearer TOKEN_FROM_LOGIN"
}
```

For uploading content, it should send request with `multipart/form-data` for `Content-Type` header. And, whenever sending a `multipart/form-data` it must contain `map` key in the post request.

For better understanding go through:

- `vendor/laragraph/utils/src/RequestParser.php#L48-L73`
- `vendor/laragraph/utils/src/RequestParser.php#L82-L117`


- Upload profile picture
```bash
curl 'http://{{HOST}}/graphql' \
  -H 'Authorization: Bearer TOKEN_FROM_LOGIN' \
  -F operations='{ "query": "mutation avatar($input: AvatarInput!) { avatar(input: $input) {__typename ... on Message { message } } }", "variables": {"input":{"profile_picture": null}} }' \
  -F map='{ "0": ["variables.input.profile_picture"] }' \
  -F 0=@/directory/to-your/image.png
```

- Remove Profile picture with `multipart/form-data` **`map` key should be present**
```bash
curl 'http://{{HOST}}/graphql' \
  -H 'Authorization: Bearer TOKEN_FROM_LOGIN' \
  -F operations='{ "query": "mutation avatar($input: AvatarInput!) { avatar(input: $input) {__typename ... on Message { message } } }", "variables": {"input":{"remove_profile_picture":true}} }' \
  -F map='{}'
```

---

### Delete confession

```graphql endpoint doc
mutation {
  deleteConfession(id: 10) {
    __typename
    ... on Message {
      message
    }
    ... on Success {
      info
    }
  }
}
```

### Header

```json
{
  "Authorization": "Bearer TOKEN_FROM_LOGIN"
}
```

---

### Post confession to user wall

```graphql endpoint doc
mutation ($input: PostConfessionInput!) {
  postConfessoin (input: $input) {
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
    "as_anonymous": true,
    "body": "this is confession text",
    "username": "confession-wall"
  }
}
```

### Header

```json
{
  "Authorization": "Bearer TOKEN_FROM_LOGIN"
}
```

---

### Get Confessions

```graphql endpoint doc
{
  myConfessions(page: 1) {
    pagination {
      has_next
      has_prev
    }
    items {
      id
      body
      is_public
      is_anonymous
      poster
      posted_at
    }
  }
}
```

### Headers

```json
{
  "Authorization": "Bearer TOKEN_FROM_LOGIN"
}
```
