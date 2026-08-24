
WORDPRESS STANDARD

Always follow WordPress best practices.

Use:

- WordPress APIs
- hooks/actions/filters
- capability checks
- nonce verification
- sanitization
- validation
- escaping
- prepared SQL queries
- WordPress coding conventions
- proper enqueue APIs
- proper REST API registration
- proper shortcode registration
- translation-ready strings
- plugin lifecycle APIs


SECURITY

Always consider:

- authentication
- authorization
- nonce validation
- capability checks
- input validation
- sanitization
- output escaping
- SQL injection
- XSS
- CSRF


ARCHITECTURE

WordPress integration should remain at the appropriate infrastructure/
integration boundary.

Domain/business logic should not become tightly coupled to WordPress APIs
unless WordPress is genuinely the required dependency.