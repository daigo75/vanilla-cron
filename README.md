#CronJobs Plugin for Vanilla 2.0

##Description

This plugins implements a Cron feature in Vanilla. It has been designed to allow 3rd party plugins to easily register themselves, allowing for some operations to be executed on a scheduled basis.

##Usage
###Registering a Cron Action

Here's how a plugin can register its Cron task(s).

1. Implement a class which exposes a public Cron method, declared as public function ```Cron()```. No parameters will passed to this method, yet. We'll calls this class ```CronTaskClass```.
2. Inside ```CronTaskClass::Cron()```, implement any action that has to be executed when Cron will run. For example, it could be the cleanup of some historical data.
3. In the Plugin Controller, implement a ```CronJobRegister``` handler, declared as public function ```CronJobsPlugin_CronJobRegister_Handler($Sender)```. In it, create an instance of ```CronTaskClass``` and register it with the command ```$Sender->RegisterCronJob($CronTaskClassInstance);```.

##Running Cron Tasks
Cron execution is triggered by simply opening the **/cron** URL in your Vanilla installation.
**Important**: by default, Cron execution can be triggered at any time by anyone. However, the Plugin implements several security mechanisms to restrict access to such feature, such as IP Filtering, Security Key and Request Throttling.

##Scheduling Cron Execution
To schedule Cron Execution, create a CronTab task which will open such URL, for example using wget. In the following example, the crontab command shown below will activate the cron tasks automatically on the hour:
```0 * * * * wget -O - -q -t 1 http://www.myvanilla.com/cron```

##Limitations
Cron will execute all registered tasks every time it runs. It's up to the 3rd party plugins to determine if it's time to do something or not. For example, Cron may scheduled to run every hour, but a plugin may want to perform its task once a day. It's up to the plugin to handle this condition accordingly.

It's possible to register each CronTaskClass instance only once. If a Plugin needs to register more than one Cron action, it will have to implement separate classes and call RegisterCronJob() multiple times.

All feedback is welcome. If you find it particularly useful, feel free to buy me a coffee! :)
