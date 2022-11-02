<?php

namespace Aziende\Error;

use Cake\Log\Log;
use Cake\Core\Configure;

class ErrorCodes {

	const MODULE_IDENTIFIER = '1';
	const ERROR_LOG_SCOPE = 'attachment_manager';

	const ERROR_POST_MAX_SIZE = self::MODULE_IDENTIFIER.'001';
	const ERROR_NO_FILE_UPLOADED = self::MODULE_IDENTIFIER.'002';
	const ERROR_UPLOAD_MAX_FILESIZE = self::MODULE_IDENTIFIER.'003';
	const ERROR_FOLDER_CREATION = self::MODULE_IDENTIFIER.'004';
	const ERROR_FILE_MOVE = self::MODULE_IDENTIFIER.'005';
	const ERROR_FILE_DB_SAVE = self::MODULE_IDENTIFIER.'006';
	const ERROR_S3_CLIENT = self::MODULE_IDENTIFIER.'007';
	const ERROR_S3_SAVE = self::MODULE_IDENTIFIER.'008';
	const ERROR_FILE_TYPE = self::MODULE_IDENTIFIER.'009';

	public static function getErrorCodes(): array {
		return [
			//1: dimensione massima POST in MB
			self::ERROR_POST_MAX_SIZE => [
				'view_message' => 'Errore: la dimensione totale massima dell\'invio è %1$s MB.',
				'log_message' => ''
			],
			self::ERROR_NO_FILE_UPLOADED => [
				'view_message' => 'Errore: nessun file è stato caricato.',
				'log_message' => ''
			],
			//1: nome del file che genera l'errore
			//2: dimensione massima del file in MB
			self::ERROR_UPLOAD_MAX_FILESIZE => [
				'view_message' => 'Errore: il file %1$s supera la dimensione massima di %2$s MB.',
				'log_message' => ''
			],
			//1: percorso di creazione della cartella
			//2: messaggio dell'eccezione
			self::ERROR_FOLDER_CREATION => [
				'view_message' => 'Errore di sistema ['.self::ERROR_FOLDER_CREATION.'].',
				'log_message' => 'Impossibile creare la cartella %1$s'.PHP_EOL.'%2$s'
			],
			//1: nome del file che genera l'errore
			//2: percorso della cartella in cui si desidera salvare il file
			//3: messaggio dell'eccezione
			self::ERROR_FILE_MOVE => [
				'view_message' => 'Errore di sistema ['.self::ERROR_FILE_MOVE.'].',
				'log_message' => 'Impossibile spostare il file %1$s nella cartella %2$s'.PHP_EOL.'%3$s'
			],
			//1: nome del file che genera l'errore
			//2: messaggio dell'eccezione
			self::ERROR_FILE_DB_SAVE => [
				'view_message' => 'Errore di sistema ['.self::ERROR_FILE_DB_SAVE.'].',
				'log_message' => 'Impossibile salvare il file %1$s nel database'.PHP_EOL.'%2$s'
			],
			//1: endpoint dell'S3 client
			//2: messaggio dell'eccezione
			self::ERROR_S3_CLIENT => [
				'view_message' => 'Errore di sistema ['.self::ERROR_S3_CLIENT.'].',
				'log_message' => 'Impossibile creare l\'istanza del client S3 %1$s'.PHP_EOL.'%2$s'
			],
			//1: nome del file che genera l'errore
			//2: percorso della cartella sul client S3 in cui si desidera salvare il file
			//3: endpoint dell'S3 client
			//4: messaggio dell'eccezione
			self::ERROR_S3_SAVE => [
				'view_message' => 'Errore di sistema ['.self::ERROR_S3_CLIENT.'].',
				'log_message' => 'Impossibile salvare il file %1$s nella cartella %2$s del client S3 %3$s'.PHP_EOL.'%4$s'
			],
			//1: nome del file che genera l'errore
			//2: l'estensione del file che genera l'errore
			//3: estensione supportata
			self::ERROR_FILE_TYPE => [
				'view_message' => 'L\'estensione del file %1$s (%2$s) non è supportata. Estensioni accettate: %3$s',
				'log_message' => ''
			]
		];
	}

	public static function getErrorCode(string $errorCode): array {
		if (array_key_exists($errorCode, self::getErrorCodes())) {
			$errorCodes = self::getErrorCodes();
			return $errorCodes[$errorCode];
		} else {
			return [];
		}
	}

	public static function logMessage(string $errorCode, array $messageParemeters): bool {
		$errorData = self::getErrorCode($errorCode);
		if (array_key_exists('log_message', $errorData) && !empty($errorData['log_message'])) {
			$logMessage = vsprintf($errorData['log_message'], $messageParemeters);
			Log::error($logMessage, self::ERROR_LOG_SCOPE);
			return true;
		}
		return false;
	}

	public static function getViewMessage(string $errorCode, array $messageParemeters): string {
		$errorData = self::getErrorCode($errorCode);
		if (array_key_exists('view_message', $errorData) && !empty($errorData['view_message'])) {
			$viewMessage = vsprintf($errorData['view_message'], $messageParemeters);
			if (Configure::read('debug') && array_key_exists('log_message', $errorData)) {
				$viewMessage .= " ".vsprintf($errorData['log_message'], $messageParemeters);
			}
			return $viewMessage;
		}
		return '';
	}
}