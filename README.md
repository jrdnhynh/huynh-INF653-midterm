# INF653 Back End Web Development - Midterm Project

**Student Name:** Jordan Huynh  
**Live Site URL:** [REPLACE WITH YOUR RENDER URL HERE]

## Project Description
A RESTful API built with PHP (OOP) and PostgreSQL to manage a database of quotes. This project is containerized using Docker and deployed on Render.com.

## Technologies Used
* **Language:** PHP 8.2
* **Database:** PostgreSQL (Render)
* **Infrastructure:** Docker & Apache
* **Deployment:** Render Web Services

## API Usage
All responses are returned in JSON format.

### Endpoints
* `GET /api/quotes/` - Returns all quotes with author and category names.
* `GET /api/authors/` - Returns a list of all authors.
* `GET /api/categories/` - Returns a list of all categories.
* `DELETE /api/quotes/` - Deletes a quote by ID (requires JSON body: `{"id": 1}`).

## Database Setup
The database schema and initial data can be found in `quotesdb.sql`. It includes:
* 5 Categories
* 19 Authors
* 35 Quotes