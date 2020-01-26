# fallr
A blogging website for falling into deep holes. Built with MySQL, Cassandra, PHP and JavaScript.

## Setup

### Installing Apache Cassandra
To try something new, this was created using the NOSQL database: Apache Cassandra. The following steps will outline how to install the requirements and package for Apache Cassandra. There were a lot of traps, so I hope someone enjoys the walkthrough.

1. Install the newest version allowed by cassandra of the OpenJDK JRE (currently it is 8). I tried this with 11 at the time which resulted in a bunch of issues that are better avoided :). 

```
sudo apt install openjdk-8-jre
```

2. Install the newest version of Python 2 (2.7 is the last version RIP python 2)
```
sudo apt install python2.7
```

3. Add the debian source to apt
```
echo "deb http://www.apache.org/dist/cassandra/debian 311x main" | sudo tee -a /etc/apt/sources.list.d/cassandra.sources.list
```

4. Add the keys for cassandra

```
curl https://www.apache.org/dist/cassandra/KEYS | sudo apt-key add -
```

5. Update apt repos
```
sudo apt-get update
```

6. Check the key
```
sudo apt-key adv --keyserver pool.sks-keyservers.net --recv-key A278B781FE4B2BDA
```

7. Install Cassandra
```
sudo apt install cassandra
```
### Installing the PHP driver (datastax)
It should be noted that a large portion of the installation process can be found on [their website](https://docs.datastax.com/en/developer/php-driver/1.3/). If you follow along, make sure that you install all of the dependencies, and their dependencies before trying to install the php-driver.

**NOTE**: This driver requires PHP 7.1 not 7.2. This also means if you have >=7.2 you will have to ensure that all php commands run as 7.1.

1. Install PHP7.1

```
sudo apt-get install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update
sudo apt-get install php7.1-dev
```

2. Set the system version of PHP to 7.1

```
sudo update-alternatives --set phpize /usr/bin/phpize7.1
sudo update-alternatives --set php /usr/bin/php7.1
sudo update-alternatives --set php-config /usr/bin/php-config7.1
```

4. Install the required C/C++ packages to build the  datastax library and get the datastax files.

```
sudo apt-get install g++ make cmake libuv-dev libssl-dev libgmp-dev openssl libpcre3-dev git libkrb5-dev zlib1g-dev

wget https://downloads.datastax.com/cpp-driver/ubuntu/18.04/dependencies/libuv/v1.34.0/libuv1_1.34.0-1_amd64.deb

wget https://downloads.datastax.com/cpp-driver/ubuntu/18.04/dependencies/libuv/v1.34.0/libuv1-dev_1.34.0-1_amd64.deb

wget https://downloads.datastax.com/cpp-driver/ubuntu/18.04/cassandra/v2.15.0/cassandra-cpp-driver-dbg_2.15.0-1_amd64.deb

wget https://downloads.datastax.com/cpp-driver/ubuntu/18.04/cassandra/v2.15.0/cassandra-cpp-driver_2.15.0-1_amd64.deb

sudo dpkg -i libuv1-dev_1.34.0-1_amd64.deb
sudo dpkg -i libuv1_1.34.0-1_amd64.deb
sudo dpkg -i cassandra-cpp-driver_2.15.0-1_amd64.deb
sudo dpkg -i cassandra-cpp-driver-dbg_2.15.0-1_amd64.deb
```

5. Clone and make the c++ driver
```
git clone https://github.com/datastax/cpp-driver.git
mkdir build
pushd build
cmake ..
make
make install
popd
```

6. Move the cassandra.h file to the right directory if it isn't already there
```
sudo cp include/cassandra.h /usr/include/
```

7. Clone the PHP repo and build it
Instead of using the PECL option (which doesn't work with very obscure errors I built it from source).

```
git clone https://github.com/datastax/php-driver.git
pushd ext
phpize
popd
mkdir build
pushd build
../ext/configure
make
make install
popd
```

At this point there is a really strange make bug that breaks echo, this can be fixed by running `export echo=echo`

### Starting Cassandra

There is an error with cassandra which can be avoided by changing the line `-XX:ThreadPriorityPolicy=49` to `-XX:ThreadPriorityPolicy=1` in the  `/etc/cassandra/jvm.options` file. 

We can start cassandra by running
```
sudo service cassandra start
```

I ran into some issues with it not running properly unless I ran it as root to start.

```
sudo cassandra -R
```