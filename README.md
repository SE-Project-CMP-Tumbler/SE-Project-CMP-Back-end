<div align="center"><h1> Mimicking Tumblr's Backend </h1></div>

<p align="center">
  <a style="text-decoration:none" >
    <img src="https://img.shields.io/badge/Language-PHP-blue" alt="Website" />
  </a>
  <a style="text-decoration:none" >
    <img src="https://img.shields.io/badge/Backend%20Framework-Laravel8-blue" alt="Website" />
  </a>
  <a style="text-decoration:none" >
    <img src="https://img.shields.io/badge/Database-PostgreSQL-blue" alt="Website" />
  </a>
  <a style="text-decoration:none" >
    <img src="https://img.shields.io/badge/Unit%20testing-PHPUnit-blue" alt="Website" />
  </a>
</p>

<p align="center">
  <a href="https://www.tumblr.com/"><img src="https://i.ibb.co/fxDJcX9/tumblr-logo.png" alt="tumblr-logo" margin="auto" /></a>
</p>

## Table Content
- [About](#About)
- [Technologies and Tools used](#Technologies-and-Tools-used)
- [Database Schema](#Database-Schema)
- [How to use the project](#How-to-use-the-project)
- [Supported Docker Containers](#Supported-Docker-Containers)
- [Functionalities Implemented](#Functionalities-Implemented)
- [ToDo](#ToDo)
- [Contributors](#Contributors)

## About
This is a fully designed and implemented backend api mimicking the social media platform [tumblr](https://www.tumblr.com/).

## Technologies and Tools used
- The API was designed and developed using Laravel8 framework.
- API documentation was written using l5-swagger.
- Complete requests collection was constructed using postman.
- Database system was built with PostgreSQL using the concepts of migration and seeds.
- Unit testing was written using PHPUnit.
- Laravel passport for OAuth authentication
- Laravel socialite for connecting with google API
- Pusher for niotifications and chat.
- Xampp.
- Composer.
- Docker.

## Database Schema
- [The DB relations of the system](https://lucid.app/lucidchart/461f4297-e4e6-4195-8d43-b8a9b72f2f32/edit?invitationId=inv_276d4b6e-a839-498a-bf0f-c0bc6e4e8cc4)
- [Continue DB relations of the system](https://lucid.app/lucidchart/520b81b1-c186-4923-98ef-195de7d02a72/edit?invitationId=inv_26000a34-432c-4adf-b437-4f696091c030)

# How to use the project
To run the project locally on your pc:
- Start Apache from XAMPP
- Clone this repositry in any folder and go inside the ```SE-Project-CMP-Back-end\Backend``` folder
```
git clone https://github.com/SE-Project-CMP-Tumbler/SE-Project-CMP-Back-end.git
cd SE-Project-CMP-Back-end/Backend
```
- Download all dependencies used in the project, by running the following command:
```
composer install
```
- Migrate and seed the database, by running the following command:
```
php artisan migrate –seed
```
- Generate the api documentation, by running the following command:
```
php artisan l5-swagger:generate
```
- Start the backend server that handles the application requests by running the following command: 
```
php artisan serve
```
To open the api documentation, and check the requests and response structures, in any browser visit the following link:
[http://127.0.0.1:8000/api/documentation](http://127.0.0.1:8000/api/documentation)

To open the full requests' collection and test the requests, open postman and import the ``` Backend-Team.postman_collection.json``` file.

## Supported Docker Containers
- Docker container running apache server to support laravel backend.
- Docker container running postgresql for storing the database.
- Docker container running ftp server and apache server to store uploaded files such as audios, images and videos into.

## Functionalities Implemented
### User Module
- User authentication (register-login-logout)
- User authentication using google emails (register with google-login with google)
- Email verification and email resending
- Password reset (sending email-actual password reset)
- User settings (change password-change email-delete user)

### Blog Module
- Get a specific blog information by his/her username
- Create, delete, show and get a blog.
- Retrieve trending blogs.
- Retrieve all likes done by the authenticated blog

### Upload Module
- Upload Image files when creating posts, sending messages in chat between blogs.
- Upload Image and Video files using external urls when creating posts.
- Upload Image, Audio and Video files in its base64 forms.
- Upload Audio, and Video files when creating posts.

### Chat Module
- Chat between two different blogs
- Chat search which allows any blogs to search for other blogs to start messaging them.
- Get the chat room id between two chatting blogs.
- Send a message in that chat room between two blogs. This message is allowed to be text, photo, or both text and photo.
- Sending that message is real-time using pusher channels web-sockets.
- Allow one blog to clear its chat room view while the other isn't.
- Get all the messages between those two chatting blogs. The returned messages will be
affected when the requesting blog last deleted its chat room.
- Get all the last messages of a blog. The returned messages will be affected when the
requesting blog last deleted its chat room.

### Notification Module
- Send a notification for the following blog when another blog follows them.
- Send a notification for the mentioned blog when another blog mentions them.
- Send a notification for the mentioned blog when another blog mentions them in a reply.
- Send a notification when a blog reblogs one of another blog posts.
- Send a notification when a blog likes one of the pots of another blog.
- Send a notification for the asked blog when another blog asks them a question.
- Send a notification for the questioner blog when the asked blog answers their questions.
- Each blog can get all of its last unread notifications of different types at once or just
request one type of these notifications.

### Follow Blog Module
- Blog can retrieve his/her Followers.
- Blog can retrieve his/her Followings
- Blog can follow and unfollow other blogs.
- Blog can search in his/her list of followers.

### Post Module
- Create a post reblogged from another post.
- Create, Edit, Delete and Retrieve a post with extracting the Tags and mentions from its body. Validation is applied.
- Retrieve all posts on a blog’s profile.
- - Get all published posts of type chat, image, ask(questions).
- Get posts in random order.
- Get posts that are trending.
- Get one of those trending posts called the radar post.
- Get all draft posts of a specific blog.
- Get all published posts of a specific type: text, quote, video, audio types.
- Get dashboard (home) posts.
    - Posts of the currently authenticated user’s following blogs.
    - Posts of the currently authenticated user.
- Allow the blog to pin/unpin one of their posts.
- Allow the blog to change the state of one of their posts to represent one of these states
published, draft, private or submission post.
### Submission Post Module
- Creates a request to submit a post on another blog’s profile.
- Retrieve all submission requests done to submit posts from other blogs on his/her profile.
- Approve a submission request done to submit a post on his/her profile.
- Reject a submission request done to submit a post on his/her profile.
- Reject all submission requests done to submit posts on any of his/her blog’s profile.

### Search Module
- Search with some words mentioned in a blogs’ posts.
- Autocomplete functionality is added.

### Tag Module
- Create a Tag.
- Get specific Tag information.
- Get all published posts where a specific tag has been mentioned in. Posts are sorted in any of two criterias, either by the most recently published or by the number of engagements(likes) they got.
- Get trending Tags where the trending criteria applied is the number of tags’ occurrences in all Posts.
- Get suggesting Tags, that the user is not following and they’re sorted from the most trending tag to the least.
- A Blog can Follow, and UnFollow a tag.
- A Blog can retrieve all tags he/she follows.
- A Blog can retrieve his/her follow status with a specific tag

### Blog setting Module
A blog can choose from his/her setting to: 
- Allow another blog to ask him/her.
- Allow another blog to post a post (submission) on his/her profile.
- Allow other blogs to reply to his/her posts.
- Control his/her ask-page’s title ,submission-page’s title, and submission guidelines.
- Allow anyone to Ask anonymously on his/her profile.

### Blog Theme Module
- Blog can Retrieve and Update his/her profile’s avatar, header image, accent color, font color, title, description, font, font weight  and avatar shape.

### Block Module
- Retrieve list of blocking blogs
- Block, and Unblock a blog
- Checking Block status with another blog.

### Post notes Module
- Create and delete a reply (can mention another blog in reply) 
- Like and unlike a post
- Getting all post notes (replies-likes-reblogs) of a post

### Ask Module:
- A blog Asks another blog
- Answer or delete a received ask
- Get all messages (asks + submissions) for one of the authenticated user blogs.
- Delete all messages (asks + submissions) for one of the authenticated user blogs.

### Blog Activity Graph:
- Each blog can get their notes graph data (timestamp, count).
- Each blog can get their new followers graph data (timestamp, count).
- Each blog can get their total followers graph data (timestamp, count) which is an
cumulative form of the new followers data.
Note: The blog can request any of this data within a certain period - such as last day, last 3 days, last 7 days or last month - for a specific time rate - such as daily or hourly.

## ToDo
- [ ] The CRUD operations for the block are implemented and working, however, the functionality of blocking by which a user can’t interact with any action his blockers have created is not implemented.
- [ ] Sharing posts on other social media platforms like facebook, etc.
- [ ] Adding GIFs in replys, chat, and posts.

## Contributors
### This masterpiece was designed, and implemented by
<table align="center">
  <tr>
    <td align="center">
    <a href="https://github.com/BigFish2086" target="_black">
    <img src="https://avatars.githubusercontent.com/u/63132227?v=4" width="100px;" alt="Ahmed Ibrahim"/>
    <br />
    <sub><b>Ahmed Ibrahim</b></sub></a>
    </td>
    <td align="center">
    <a href="https://github.com/radwaahmed2132000" target="_black">
    <img src="https://avatars.githubusercontent.com/u/56734728?v=4" width="100px;" alt="Radwa Ahmed"/>
    <br />
    <sub><b>Radwa Ahmed</b></sub></a>
    </td>
    <td align="center">
    <a href="https://github.com/Makrion" target="_black">
    <img src="https://avatars.githubusercontent.com/u/62072554?v=4" width="100px;" alt="Michael"/>
    <br />
    <sub><b>Michael</b></sub></a>
    </td>
    <td align="center">
    <a href="https://github.com/NouranHany" target="_black">
    <img src="https://avatars.githubusercontent.com/u/59095993?v=4" width="100px;" alt="Noran Hany"/>
    <br />
    <sub><b>Noran Hany</b></sub></a>
    </td>
  </tr>
 </table>
