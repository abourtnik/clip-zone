<h1 align="center">
  <a href="https://www.clip-zone.com">
    <img src="https://www.clip-zone.com/images/logo.png" alt="ClipZone logo" height="200">
  </a>
  <br>
    ClipZone
  <br>
</h1>

<h3 align="center">Open Source Youtube Clone</h3>


## Technical detail

* Backend : PHP 8.1, Laravel 9 , MySQL
* Frontend : Preact, Alpine JS, Boostrap 5.2

## Installation

* Clone project
* Run `make init` command on the root folder

Then go to `http://localhost:8080`

## Run seeder

```shell
make reset
```

## Storage

If you use the ***local*** FILESYSTEM_DISK:

- Update docker/nginx.conf file : comment *S3 FILESYSTEM_DISK block* and uncomment *LOCAL FILESYSTEM_DISK* block 

If you use the ***s3*** FILESYSTEM_DISK: 

- Update docker/nginx.conf file : comment *LOCAL FILESYSTEM_DISK* block and uncomment *S3 FILESYSTEM_DISK* block
- Connect to http://localhost:8900 with credentials defined in minio section in docker-compose.yml
- Create new bucket and adjust AWS_BUCKET & AWS_URL env variables + proxy_pass directive in nginx
- Define following policy for the created bucket
- Generate secret key and insert this last one in **proxy_set_header User-Agent** in nginx and **aws:UserAgent** in json policy

```json
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Sid": "Public Access",
            "Effect":"Allow",
            "Principal":{
                "AWS":"*"
            },
            "Action":["s3:GetObject"],
            "Resource":[
                "arn:aws:s3:::clipzone/avatars/*",
                "arn:aws:s3:::clipzone/banners/*",
                "arn:aws:s3:::clipzone/categories/*"
            ]
        },
        {
            "Sid": "Video Access",
            "Effect":"Allow",
            "Principal":{
                "AWS":"*"
            },
            "Action":["s3:GetObject"],
            "Resource":[
                "arn:aws:s3:::clipzone/videos/*"
            ],
            "Condition": {
                "StringLike": {
                    "aws:UserAgent": "SECRET"
                }
            }
        }
    ]
}
```

## Contributing

We encourage you to contribute to ClipZone !!

Trying to report a possible security vulnerability in ClipZone ? Consider using email :
**contact@antonbourtnik.fr** with clear description of security vulnerability.

## License
ClipZone is made available under the [MIT License](http://www.opensource.org/licenses/mit-license.php).

## Credits
ClipZone is created and maintained by [Anton Bourtnik](https://github.com/abourtnik)
