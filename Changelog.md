# Change Log

The change log describes what is "Added", "Removed", "Changed" or "Fixed" between each release. 

## 0.3.0

### Added

- `MessageInterface`

### Changed

- The `Message` is now immutable. Replaced "setters" with "withers".
- `Storage::create()` and `Storage::update()` has updated signatures to use the`MessageInterface`.

## 0.2.3

### Added

- Support for Symfony 4

## 0.2.2

### Changed

- Documentation change on interface. 

## 0.2.1

### Added

- `TransferableStorage`

### Changed

- `Message` is now final

## 0.2.0

### Added

- `Storage::create(Message $message)`

## 0.1.0

Init release
