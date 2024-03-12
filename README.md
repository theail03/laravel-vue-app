# Image Matrix Full Stack Application

Built with these technologies
<table>
    <tr>
        <td>
            <a href="https://laravel.com"><img src="https://i.imgur.com/pBNT1yy.png" /></a>
        </td>
        <td>
            <a href="https://vuejs.org/"><img src="https://i.imgur.com/BxQe48y.png" /></a>
        </td>
        <td>
            <a href="https://tailwindcss.com/"><img src="https://i.imgur.com/wdYXsgR.png" /></a>
        </td>
    </tr>
</table> 


## Requirements
You need to have PHP version **8.0** or above. Node.js version **12.0** or above.

## Installation

#### Backend
1. Clone the project
2. Go to the project root directory
3. Run `composer install`
4. Create database
5. Copy `.env.example` into `.env` file and adjust parameters
6. Run `php artisan serve` to start the project at http://localhost:8000

#### Frontend
1. Navigate to `vue` folder using terminal
2. Run `npm install` to install vue.js project dependencies
3. Copy `vue/.env.example` into `vue/.env` and specify API URL
4. Start frontend by running `npm run dev`
5. Open http://localhost:3000


## License

The project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Notes

To integrate Cloudinary's services for image management, go to the Cloudinary Console, navigate to the Dashboard and locate the `API environment variable` which is a ready-to-use string with all your credentials.

Execute the command `php artisan key:generate` to populate the APP_KEY variable in the .env file.

To seed your database with initial data, run `php artisan db:seed`.

## Links

- [I Built Full Stack Application with Laravel, Vue 3 and Tailwindcss](https://www.youtube.com/watch?v=WLQDpY7lOLg)
- [Login con Laravel 10 y Google | SSO Fácil con Laravel Socialite](https://www.youtube.com/watch?v=C98LvIbPSf0)
- [Upload image with laravel and cloudinary - 1](https://www.youtube.com/watch?v=ekJ3LZs2yu8)
- [Upload image with laravel and cloudinary - 2](https://www.youtube.com/watch?v=LaSLfXoLYYA)
- [Tutorial Laravel Vue Deploy a Hostinger](https://www.youtube.com/watch?v=O7jJMGQai2U)
- [Tutorial env variables de google y cloudinary en hostinger](https://youtu.be/VZy6CkdUjkw)