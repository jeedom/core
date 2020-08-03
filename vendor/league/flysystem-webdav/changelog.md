# Changelog

## 1.0.9 - 2019-11-13

* Made detection of directories compliant with rfc4918 (#53).
* Allow higher deps versions.

## 1.0.8 - 2019-07-08

* Made some properties private to allow them to be overwritten so other types of WebDAV servers can be supported (#51).

## 1.0.7 - 2019-06-23

* Fixed prefix handling from listContent

## 1.0.6 - 2018-12-14

### Fixed

* Create directories with a trailing "/".

## 1.0.5 - 2016-12-14

### Fixed

* Copy now also works with native webdav capabilities.

## 1.0.4 - 2016-08-11

### Fixed

* The getMetadata function now also catches http exceptions.

## 1.0.3 - 2016-07-11

### Improved

* The `sabre/dav` version is upped to ~3.1.

## 1.0.2 - 2016-03-17

### Improved

* Listing contents now prevents 301 redirects by adding a trailing slash.

## 1.0.1 - 2015-05-18

### Fixed

* Corrected namespace for missing object exception catching.
* Corrected last-modified return type handling (int -> [int])


## 1.0.0

Initial release
