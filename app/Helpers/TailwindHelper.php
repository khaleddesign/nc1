<?php

namespace App\Helpers;

/**
 * Helper pour centraliser les classes Tailwind CSS utilisées dans l'application
 */
class TailwindHelper
{
    /**
     * Classes pour les badges de statut
     */
    public static function badge(string $type = 'primary'): string
    {
        $classes = [
            'primary' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800',
            'secondary' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800',
            'success' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800',
            'danger' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800',
            'warning' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800',
            'info' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800',
        ];

        return $classes[$type] ?? $classes['primary'];
    }

    /**
     * Classes pour les boutons
     */
    public static function button(string $type = 'primary', string $size = 'md'): string
    {
        $baseClasses = 'inline-flex items-center justify-center border border-transparent font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-150 disabled:opacity-50 disabled:cursor-not-allowed';
        
        $sizeClasses = [
            'xs' => 'px-2.5 py-1.5 text-xs',
            'sm' => 'px-3 py-2 text-sm leading-4',
            'md' => 'px-4 py-2 text-sm',
            'lg' => 'px-4 py-2 text-base',
            'xl' => 'px-6 py-3 text-base',
        ];

        $typeClasses = [
            'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 active:bg-blue-800',
            'secondary' => 'bg-gray-600 text-white hover:bg-gray-700 focus:ring-gray-500 active:bg-gray-800',
            'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500 active:bg-green-800',
            'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 active:bg-red-800',
            'warning' => 'bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-yellow-500 active:bg-yellow-700',
            'outline' => 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-blue-500',
            'ghost' => 'bg-transparent text-gray-700 hover:bg-gray-100 focus:ring-gray-500',
        ];

        $size = $sizeClasses[$size] ?? $sizeClasses['md'];
        $type = $typeClasses[$type] ?? $typeClasses['primary'];

        return trim("$baseClasses $size $type");
    }

    /**
     * Classes pour les alertes
     */
    public static function alert(string $type = 'info'): string
    {
        $classes = [
            'success' => 'bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md',
            'error' => 'bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md',
            'warning' => 'bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-md',
            'info' => 'bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-md',
        ];

        return $classes[$type] ?? $classes['info'];
    }

    /**
     * Classes pour les inputs de formulaire
     */
    public static function input(string $state = 'default'): string
    {
        $baseClasses = 'block w-full rounded-md shadow-sm sm:text-sm';
        
        $stateClasses = [
            'default' => 'border-gray-300 focus:border-blue-500 focus:ring-blue-500',
            'error' => 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500',
            'success' => 'border-green-300 text-green-900 placeholder-green-300 focus:border-green-500 focus:ring-green-500',
        ];

        $state = $stateClasses[$state] ?? $stateClasses['default'];
        return trim("$baseClasses $state");
    }

    /**
     * Classes pour les cartes
     */
    public static function card(string $variant = 'default'): string
    {
        $classes = [
            'default' => 'bg-white shadow-sm rounded-lg border border-gray-200',
            'elevated' => 'bg-white shadow-md rounded-lg border border-gray-200',
            'outlined' => 'bg-white border-2 border-gray-200 rounded-lg',
            'flat' => 'bg-white border border-gray-100 rounded-lg',
        ];

        return $classes[$variant] ?? $classes['default'];
    }

    /**
     * Classes pour les tables
     */
    public static function table(): array
    {
        return [
            'container' => 'overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg',
            'table' => 'min-w-full divide-y divide-gray-300',
            'thead' => 'bg-gray-50',
            'th' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider',
            'tbody' => 'bg-white divide-y divide-gray-200',
            'td' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-900',
            'row_hover' => 'hover:bg-gray-50',
            'row_striped' => 'odd:bg-white even:bg-gray-50',
        ];
    }

    /**
     * Classes pour la navigation
     */
    public static function nav(): array
    {
        return [
            'container' => 'bg-white shadow',
            'wrapper' => 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8',
            'nav' => 'flex justify-between h-16',
            'link_active' => 'inline-flex items-center px-1 pt-1 border-b-2 border-blue-500 text-sm font-medium text-gray-900',
            'link_inactive' => 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300',
            'mobile_menu' => 'sm:hidden',
            'mobile_link_active' => 'block pl-3 pr-4 py-2 border-l-4 border-blue-500 text-base font-medium text-blue-700 bg-blue-50',
            'mobile_link_inactive' => 'block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300',
        ];
    }

    /**
     * Classes pour les modales
     */
    public static function modal(): array
    {
        return [
            'overlay' => 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50',
            'container' => 'flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0',
            'content' => 'inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full',
            'header' => 'bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4',
            'body' => 'bg-white px-4 pt-5 pb-4 sm:p-6',
            'footer' => 'bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse',
        ];
    }

    /**
     * Classes spécifiques aux chantiers
     */
    public static function chantierStatut(string $statut): string
    {
        $classes = [
            'planifie' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800',
            'en_cours' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800',
            'termine' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800',
        ];

        return $classes[$statut] ?? $classes['planifie'];
    }

    /**
     * Classes pour les barres de progression
     */
    public static function progressBar(string $color = 'blue'): array
    {
        $colors = [
            'blue' => 'bg-blue-500',
            'green' => 'bg-green-500',
            'yellow' => 'bg-yellow-500',
            'red' => 'bg-red-500',
            'gray' => 'bg-gray-400',
        ];

        return [
            'container' => 'w-full bg-gray-200 rounded-full h-2.5',
            'bar' => ($colors[$color] ?? $colors['blue']) . ' h-2.5 rounded-full transition-all duration-300 ease-in-out',
        ];
    }

    /**
     * Classes pour les notifications/toasts
     */
    public static function toast(string $type = 'info'): string
    {
        $classes = [
            'success' => 'fixed top-4 right-4 z-50 max-w-sm w-full bg-white shadow-lg rounded-lg border-l-4 border-green-400 p-4',
            'error' => 'fixed top-4 right-4 z-50 max-w-sm w-full bg-white shadow-lg rounded-lg border-l-4 border-red-400 p-4',
            'warning' => 'fixed top-4 right-4 z-50 max-w-sm w-full bg-white shadow-lg rounded-lg border-l-4 border-yellow-400 p-4',
            'info' => 'fixed top-4 right-4 z-50 max-w-sm w-full bg-white shadow-lg rounded-lg border-l-4 border-blue-400 p-4',
        ];

        return $classes[$type] ?? $classes['info'];
    }

    /**
     * Classes pour les icônes selon le contexte
     */
    public static function icon(string $context = 'default'): string
    {
        $classes = [
            'default' => 'w-5 h-5',
            'small' => 'w-4 h-4',
            'large' => 'w-6 h-6',
            'button' => 'w-4 h-4 mr-2',
            'nav' => 'w-5 h-5 mr-3',
            'status' => 'w-3 h-3',
        ];

        return $classes[$context] ?? $classes['default'];
    }

    /**
     * Classes responsives pour les grilles
     */
    public static function grid(int $cols = 1): string
    {
        $classes = [
            1 => 'grid grid-cols-1',
            2 => 'grid grid-cols-1 md:grid-cols-2',
            3 => 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
            4 => 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
            6 => 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6',
        ];

        return $classes[$cols] ?? $classes[1];
    }

    /**
     * Classes pour l'espacement
     */
    public static function spacing(string $type = 'default'): string
    {
        $classes = [
            'default' => 'space-y-4',
            'tight' => 'space-y-2',
            'loose' => 'space-y-6',
            'section' => 'space-y-8',
            'page' => 'space-y-12',
        ];

        return $classes[$type] ?? $classes['default'];
    }
}