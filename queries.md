## Graphql Queries from the frontend using playground

### Named or anonymous/shorthand queries

```graphql
# query hello_query {
#   hello
# }

# query {
#   custom_resolver
# }

# {
#   custom_resolver_with_method
# }

# {
#   greet(name: "Anik")
# }

# query this_is_called_operation_name($name: String!) {
#   greet(name: $name)
# }

# query get_greet_with_optional_age ($name: String!) {
#   greet_with_optional_age(name: $name)
# }

# query get_greet_with_optional_age ($name: String!, $age: Int) {
#   greet_with_optional_age(name: $name, age: $age)
# }

# query greet_with_alias ($name: String!) {
#   alias_name: greet(name: $name)
# }
```

# VARIABLES
```json 
{
  "name": "Syed Sirajul Islam Anik",
  "age": 28
}
```

---

