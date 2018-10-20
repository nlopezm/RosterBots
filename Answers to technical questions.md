## 1. How long did you spend on the code challenge? What would you add to your solution if you had more time? If you didn't spend much time on the code challenge then use this as an opportunity to explain what you would add.

It took me about 10 hours to develop the backend and 15 the front end.
If I had more time, I would add:
- Better error handling
- Implement security issues
- Add custom logging
- Add more unit, functional and end to end test to the frontend.

## 2. What was the most useful feature that was added to the latest version of a language you used? Please include a snippet of code that shows how you've used it.

I think that two of the most useful features are types and return types declarations, because it 
facilitates the obtaining of the desired results and make the code easier to read.

Example:
```php
function getFullName() : string {
      return $this->firstName . ' ' . $this->lastName;
 }
```

## 3. How would you track down a performance issue in production? Have you ever had to do this?

I would implement a good logging system to know what the problems are due to. It is desirable to use a tool to audit logs such as Elastic Search, which in addition to allowing us to perform searches, tracking and monitoring, provides metrics that can facilitate decision making.
Yes, sometimes I have to participate on  track down a performance issue in production.

## 4. Please describe yourself using JSON.
'''json
{
   "name":"Nahuel",
   "lastname":"López",
   "age":21,
   "country":"Argentina",
   "education":[
      {
         "carreer":"Computer Engineering",
         "where":"UADE",
         "status":"On going (4th)"
      },
      {
         "carreer":"University Technician in Software Development",
         "where":"UADE",
         "status":"Graduated (2018)"
      },
      {
         "carreer":"Computer Analyst",
         "where":"UADE",
         "status":"Graduated (2017)"
      }
   ],
   "skills":{
      "programmingLanguages":[
         "PHP",
         "Javascript",
         "Typescript",
         "JAVA"
      ],
      "frameworks":[
         "Symfony",
         "Angular",
         "AngularJS",
         "Ionic",
         "Slim Framework"
      ],
      "db":[
         "MySQL",
         "MS SQL Server",
         "Redis"
      ],
      "aws":[
         "S3",
         "Elastic Beanstalk",
         "Lambda",
         "API Gateway",
         "DynamoDB",
         "Route53",
         "EC2"
      ],
      "os":[
         "Linux",
         "Windows"
      ],
      "other":[
         "SCSS",
         "CSS",
         "HTML",
         "Git",
         "Apache",
         "Apache Cordova",
         "RESTful",
         "IBM MQ",
         "Android Studio",
         "Android SDK",
         "Azure Face",
         "Scrum",
         "JIRA"
      ]
   },
   "passions":[
      "Sports",
      "Soccer",
      "IT",
      "Learning"
   ]
}
'''
