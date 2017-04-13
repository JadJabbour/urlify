URLify: URL shortening for content -- exercise

Requirements:

- apache/php with mod_rewrite module

Installation [quick]:

- Copy urlify folder into your apache's public www folder
- API url: "http://localhost/"
- Tests url: "http://localhost/tests"

API:

*	POST /create_short_url

 	Payload type: JSON

 	Payload: { text: "My text to share", short_url: “abc123” }

	Response: { short_url: "abc123" }

	Error: { error: "Something went wrong" }


*	POST /remove/:short_url

 	Response type: JSON

	Response: { success: "Content successfully removed" }

	Error: { error: "Something went wrong" }


*	GET /retrieve_text/:short_url
	
	Response type: JSON

	Response: { text: "My text to share" }

    Error: { error: "Not found" }


* 	GET /stats

	Response type: JSON

	Response:{ total_snippets: 10, total_size_in_mb: 0.5, total_characters_stored: 1000 }


* 	GET /all

	Response type: JSON

	Response:[{ short_url: text }, ... ]
