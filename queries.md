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

# query greet_as_default {
#   alias_name: greet_with_default_value
# }

# Don't pass variable, otherwise the default value will be overwritten
# query ($name : String = "Default value on client side") {
#   greet_with_default_value(name: $name)
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

