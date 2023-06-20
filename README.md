[![Preview project](https://img.shields.io/static/v1?label=Laravel&message=Preview&color=red&style=flat&logo=Laravel)][preview]

# Ticketi

Project of application for selling tickets for events. [Click here to preview.][preview]

[preview]: https://ticketi.fullweb.net.pl/

### Table of content

-   [Technologies](#technologies)
-   [Features](#features)
-   [Diagrams](#diagrams)
-   [AJAX Endpoints](#ajax-endpoints)

---

### Technologies

-   PHP 7.4
-   Laravel 8
-   JavaScript
-   Bootstrap 5
-   MySQL
-   Google Maps Embed API
-   Google Geocoding API

---

### Features

-   [x] Login and registration
-   [x] User profile
-   [x] Administration panel with orders statistic
-   [x] Events filtering
    -   [x] Fulltext search on event name, description and tags
    -   [x] Events in cities of distance up to 10km
-   [x] Sending pdf tickets to verified email
-   [x] Contact form

![Administration panel preview](https://github.com/dominikjalowiecki/Ticketi/assets/116639110/e9627256-9891-4816-8eeb-2ddc49c1a6b3)

---

### Diagrams

#### Database Relational Diagram

![Database Relational Diagram](https://github.com/dominikjalowiecki/Ticketi/assets/116639110/80326512-deb5-4768-b299-41de1a0b385e)

#### UML Use Case Diagram

![UML Use Case Diagram](https://github.com/dominikjalowiecki/Ticketi/assets/116639110/3a6fd036-b398-4d49-ad75-d85b06e257be)

---

### AJAX Endpoints

|                Endpoint                 |  Method  |     Request body     | Authorization |
| :-------------------------------------: | :------: | :------------------: | :-----------: |
|            /stats/categories            |  `GET`   |          -           |   MODERATOR   |
|  /stats/revenue/{daily/monthly/annual}  |  `GET`   |          -           |   MODERATOR   |
|        /stats/cities?s={string}         |  `GET`   |          -           |   MODERATOR   |
|             /event/comment              | `DELETE` |      idComment       |   MODERATOR   |
|             /event/comment              |  `POST`  | idEvent <br> comment |     USER      |
|              /event/follow              |  `POST`  |       idEvent        |     USER      |
| /event/comments?idEvent={id}&page={int} |  `GET`   |          -           |       -       |
|           /event/like-comment           |  `POST`  |      idComment       |       -       |
|              /event/likes               |  `POST`  |       idEvent        |       -       |
