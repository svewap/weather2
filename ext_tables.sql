#
# Table structure for table 'tx_weather2_domain_model_weather'
#
CREATE TABLE tx_weather2_domain_model_weather
(

    uid              int(11)                         NOT NULL auto_increment,
    pid              int(11)             DEFAULT '0' NOT NULL,

    place_name       varchar(255)        DEFAULT ''  NOT NULL,
    date_max         int(11)                         NOT NULL,
    date_min         int(11)                         NOT NULL,
    task_id          int(11)                         NOT NULL,
    serialized_array text                DEFAULT ''  NOT NULL,

    tstamp           int(11) unsigned    DEFAULT '0' NOT NULL,
    crdate           int(11) unsigned    DEFAULT '0' NOT NULL,
    cruser_id        int(11) unsigned    DEFAULT '0' NOT NULL,
    deleted          tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden           tinyint(4) unsigned DEFAULT '0' NOT NULL,
    starttime        int(11) unsigned    DEFAULT '0' NOT NULL,
    endtime          int(11) unsigned    DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)

);

#
# Table structure for table 'tx_weather2_domain_model_weatheralert'
#
CREATE TABLE tx_weather2_domain_model_weatheralert
(

    uid                     int(11)                         NOT NULL auto_increment,
    pid                     int(11)             DEFAULT '0' NOT NULL,

    dwd_warn_cell           int(11)                         NOT NULL,
    level                   int(11)             DEFAULT '0' NOT NULL,
    type                    int(11)             DEFAULT '0' NOT NULL,
    title                   varchar(255)        DEFAULT ''  NOT NULL,
    description             varchar(255)        DEFAULT ''  NOT NULL,
    instruction             text                DEFAULT ''  NOT NULL,
    response_timestamp      int(11)             DEFAULT '0' NOT NULL,
    start_date              int(11) unsigned    DEFAULT '0' NOT NULL,
    end_date                int(11) unsigned    DEFAULT '0' NOT NULL,
    comparison_hash         varchar(32)         DEFAULT ''  NOT NULL,
    preliminary_information tinyint(4) unsigned DEFAULT '0' NOT NULL,

    tstamp                  int(11) unsigned    DEFAULT '0' NOT NULL,
    crdate                  int(11) unsigned    DEFAULT '0' NOT NULL,
    cruser_id               int(11) unsigned    DEFAULT '0' NOT NULL,
    deleted                 tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden                  tinyint(4) unsigned DEFAULT '0' NOT NULL,
    starttime               int(11) unsigned    DEFAULT '0' NOT NULL,
    endtime                 int(11) unsigned    DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)

);

#
# Table structure for table 'tx_weather2_domain_model_dwdwarncell'
#
CREATE TABLE tx_weather2_domain_model_dwdwarncell
(

    uid          int(11)                         NOT NULL auto_increment,
    pid          int(11)             DEFAULT '0' NOT NULL,

    warn_cell_id varchar(30)         DEFAULT ''  NOT NULL,
    name         varchar(60)         DEFAULT ''  NOT NULL,
    short_name   varchar(30)         DEFAULT ''  NOT NULL,
    sign         varchar(10)         DEFAULT ''  NOT NULL,

    tstamp       int(11) unsigned    DEFAULT '0' NOT NULL,
    crdate       int(11) unsigned    DEFAULT '0' NOT NULL,
    cruser_id    int(11) unsigned    DEFAULT '0' NOT NULL,
    deleted      tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden       tinyint(4) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)

);
