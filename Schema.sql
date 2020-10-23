-- Adminer 4.7.7 PostgreSQL dump

DROP TABLE IF EXISTS "forums";
DROP SEQUENCE IF EXISTS forums_id_seq;
CREATE SEQUENCE forums_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;

CREATE TABLE "public"."forums" (
    "id" integer DEFAULT nextval('forums_id_seq') NOT NULL,
    "name" character(255) NOT NULL,
    "slug" character(255) DEFAULT '' NOT NULL,
    CONSTRAINT "forums_id" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "messages";
DROP SEQUENCE IF EXISTS messages_id_seq;
CREATE SEQUENCE messages_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;

CREATE TABLE "public"."messages" (
    "id" integer DEFAULT nextval('messages_id_seq') NOT NULL,
    "forum" bigint NOT NULL,
    "topic" bigint NOT NULL,
    "author" bigint NOT NULL,
    "content" text NOT NULL,
    "timestamp" bigint NOT NULL,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "last_edit" bigint DEFAULT '0' NOT NULL,
    "edited_by" bigint DEFAULT '0' NOT NULL
) WITH (oids = false);


DROP TABLE IF EXISTS "messages_history";
DROP SEQUENCE IF EXISTS messages_editions_id_seq;
CREATE SEQUENCE messages_editions_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;

CREATE TABLE "public"."messages_history" (
    "id" integer DEFAULT nextval('messages_editions_id_seq') NOT NULL,
    "message" bigint NOT NULL,
    "content" text NOT NULL,
    "timestamp" bigint NOT NULL,
    "edited_by" bigint NOT NULL
) WITH (oids = false);


DROP TABLE IF EXISTS "pm";
DROP SEQUENCE IF EXISTS pm_id_seq;
CREATE SEQUENCE pm_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;

CREATE TABLE "public"."pm" (
    "id" integer DEFAULT nextval('pm_id_seq') NOT NULL,
    "title" character(100) NOT NULL,
    "timestamp" bigint NOT NULL,
    "author" bigint NOT NULL,
    CONSTRAINT "pm_id" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "pm_messages";
DROP SEQUENCE IF EXISTS pm_messages_id_seq;
CREATE SEQUENCE pm_messages_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;

CREATE TABLE "public"."pm_messages" (
    "id" integer DEFAULT nextval('pm_messages_id_seq') NOT NULL,
    "pm" bigint NOT NULL,
    "author" bigint NOT NULL,
    "content" text NOT NULL,
    "timestamp" bigint NOT NULL
) WITH (oids = false);


DROP TABLE IF EXISTS "pm_receivers";
DROP SEQUENCE IF EXISTS pm_receivers_id_seq;
CREATE SEQUENCE pm_receivers_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;

CREATE TABLE "public"."pm_receivers" (
    "id" integer DEFAULT nextval('pm_receivers_id_seq') NOT NULL,
    "pm_id" bigint NOT NULL,
    "user_id" bigint NOT NULL,
    "timestamp" bigint NOT NULL
) WITH (oids = false);


DROP TABLE IF EXISTS "polls";
DROP SEQUENCE IF EXISTS polls_id_seq;
CREATE SEQUENCE polls_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;

CREATE TABLE "public"."polls" (
    "id" integer DEFAULT nextval('polls_id_seq') NOT NULL,
    "topic" bigint NOT NULL,
    "question" character(255) NOT NULL,
    "points" bigint NOT NULL
) WITH (oids = false);


DROP TABLE IF EXISTS "polls_responses";
DROP SEQUENCE IF EXISTS polls_responses_id_seq;
CREATE SEQUENCE polls_responses_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;

CREATE TABLE "public"."polls_responses" (
    "id" integer DEFAULT nextval('polls_responses_id_seq') NOT NULL,
    "topic" bigint NOT NULL,
    "response" character(255) NOT NULL,
    "votes" bigint DEFAULT '0' NOT NULL
) WITH (oids = false);


DROP TABLE IF EXISTS "polls_votes";
DROP SEQUENCE IF EXISTS polls_votes_id_seq;
CREATE SEQUENCE polls_votes_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;

CREATE TABLE "public"."polls_votes" (
    "id" integer DEFAULT nextval('polls_votes_id_seq') NOT NULL,
    "topic" bigint NOT NULL,
    "user_id" bigint NOT NULL,
    "response" bigint NOT NULL
) WITH (oids = false);


DROP TABLE IF EXISTS "sessions";
DROP SEQUENCE IF EXISTS sessions_id_seq;
CREATE SEQUENCE sessions_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;

CREATE TABLE "public"."sessions" (
    "id" integer DEFAULT nextval('sessions_id_seq') NOT NULL,
    "name" character(40) NOT NULL,
    "user_id" bigint NOT NULL,
    "admin" smallint DEFAULT '0' NOT NULL,
    "ip" character(45) NOT NULL,
    "first_seen" bigint NOT NULL,
    "last_seen" bigint NOT NULL,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "sessions_id" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "stickers";
DROP SEQUENCE IF EXISTS stickers_tags_id_seq;
CREATE SEQUENCE stickers_tags_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;

CREATE TABLE "public"."stickers" (
    "id" integer DEFAULT nextval('stickers_tags_id_seq') NOT NULL,
    "tags" character(255) NOT NULL,
    "ext" character(4) NOT NULL
) WITH (oids = false);


DROP TABLE IF EXISTS "topics";
DROP SEQUENCE IF EXISTS topics_id_seq;
CREATE SEQUENCE topics_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;

CREATE TABLE "public"."topics" (
    "id" integer DEFAULT nextval('topics_id_seq') NOT NULL,
    "forum" bigint NOT NULL,
    "author" bigint NOT NULL,
    "title" character(100) NOT NULL,
    "last_message_timestamp" bigint NOT NULL,
    "replies" bigint DEFAULT '0' NOT NULL,
    "pinned" smallint DEFAULT '0' NOT NULL,
    "locked" smallint DEFAULT '0' NOT NULL,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "topics_id" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "users";
DROP SEQUENCE IF EXISTS users_id_seq;
CREATE SEQUENCE users_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1;

CREATE TABLE "public"."users" (
    "id" integer DEFAULT nextval('users_id_seq') NOT NULL,
    "username" character(20) NOT NULL,
    "email" character(255) NOT NULL,
    "points" smallint DEFAULT '0' NOT NULL,
    "messages" smallint DEFAULT '0' NOT NULL,
    "avatar" character(255) DEFAULT 'default' NOT NULL,
    "password" character(255) NOT NULL,
    "password_reset_hash" character(255) DEFAULT '' NOT NULL,
    "admin" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "users_id" PRIMARY KEY ("id")
) WITH (oids = false);


-- 2020-10-23 19:26:45.406464+02
