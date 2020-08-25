<?php

namespace Erap\Core;

// exit si accès direct.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enregistre toutes les actions et les filtres pour le plugin
 */

if ( ! class_exists( Loader::class ) ) {
	/**
	 * Enregistre toutes les actions et les filtres pour le plugin.
	 *
	 * Maintien une liste de tous les hooks qui sont enregistrés par le plugin, et les enregistre avec l'API WordPress.
	 * Appeler la fonction run pour exécuter la list des actions et des filtres.
	 */
	class Loader {

		/**
		 * Le tableau des actions enregistrées par WordPress.
		 *
		 * @var      array $actions Les actions enregistrées par WordPress à lancer quand le plugin est chargé.
		 */
		protected $actions;

		/**
		 * Le tableau des actions enregistrés par WordPress.
		 *
		 * @var      array $filters Les filtres enregistrés par WordPress à lancer quand le plugin est chargé.
		 */
		protected $filters;

		/**
		 * Initialise les collections utilisés pour maintenir les actions et les filtres.
		 */
		public function __construct() {
			$this->actions = [];
			$this->filters = [];
		}

		/**
		 * Ajoute une nouvelle action à la collection pour être enregistré par WordPress.
		 *
		 * @param string $hook          Le nom de l'action enregistrée.
		 * @param object $component     Une référence à l'instance de l'objet ou l'action est définie.
		 * @param string $callback      Le nom de la fonction définie dans $component.
		 * @param int    $priority      Optionel. La priorité de la fonction. Defaut 10.
		 * @param int    $accepted_args Optionel. Le nombre d'argument à passer à $callback. Defaut 1.
		 */
		public function add_action( string $hook, $component, string $callback, int $priority = 10, int $accepted_args = 1 ): void {
			$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
		}

		/**
		 * Une fonction utilitaire pour enregistrer les actions et les hooks dans une seule collection.
		 *
		 * @param array  $hooks         La collection de hooks à enregistrer.
		 * @param string $hook          Le nom du filtre WordPress enregistré.
		 * @param object $component     Une référence à l'instance de l'objet ou le filtre est défini.
		 * @param string $callback      Le nom de la fonction définie dans $component.
		 * @param int    $priority      La priorité de la fonction.
		 * @param int    $accepted_args Le nombre d'argument à passer à $callback.
		 *
		 * @return   array                                  La collection d'actions et de filtres enregistrée par WordPress.
		 */
		private function add( array $hooks, string $hook, $component, string $callback, int $priority, int $accepted_args ): array {
			$hooks[] = [
				'hook'          => $hook,
				'component'     => $component,
				'callback'      => $callback,
				'priority'      => $priority,
				'accepted_args' => $accepted_args,
			];

			return $hooks;
		}

		/**
		 * Ajoute un nouveau filtre à la collection pour être enregistré par WordPress.
		 *
		 * @param string $hook          Le nom du filtre WordPress enregistré.
		 * @param object $component     Une référence à l'instance de l'objet ou le filtre est défini.
		 * @param string $callback      Le nom de la fonction définie dans $component.
		 * @param int    $priority      OptionEl. La priorité de la fonction. Defaut 10.
		 * @param int    $accepted_args Optional. Le nombre d'argument à passer à $callback. Defaut 1.
		 */
		public function add_filter( string $hook, $component, string $callback, int $priority = 10, int $accepted_args = 1 ): void {
			$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
		}

		/**
		 * Enregistre les actions et les filtres dans WordPress.
		 */
		public function run(): void {
			foreach ( $this->filters as $hook ) {
				add_filter( $hook['hook'], [ $hook['component'], $hook['callback'] ], $hook['priority'], $hook['accepted_args'] );
			}

			foreach ( $this->actions as $hook ) {
				add_action( $hook['hook'], [ $hook['component'], $hook['callback'] ], $hook['priority'], $hook['accepted_args'] );
			}
		}
	}
}
