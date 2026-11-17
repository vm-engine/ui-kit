<?php

declare(strict_types=1);

namespace VmEngine\UiKit\Support;

/**
 * Tailwind class strings shared by ui-kit views so custom markup matches Flux 2.x
 * (copied from Flux 2.20.1 input/index.blade.php and menu/index.blade.php).
 * Kept here (not repeated per view) so one Flux restyle is one edit.
 */
final class Styles
{
    /** Flux outline field surface without height/vertical padding (for multi-line triggers). */
    public const FIELD = 'w-full border block appearance-none text-base sm:text-sm rounded-lg ps-3 bg-white dark:bg-white/10 text-zinc-700 placeholder-zinc-400 dark:text-zinc-300 dark:placeholder-zinc-400 shadow-xs border-zinc-200 border-b-zinc-300/80 dark:border-white/10 dark:shadow-none disabled:opacity-75 focus:outline-hidden focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 focus-visible:ring-offset-accent-foreground';

    /** Flux outline input, default size (h-10). Views add their own pe-*. */
    public const INPUT = self::FIELD.' py-2 h-10 leading-[1.375rem]';

    /** Flux menu/popover surface. */
    public const POPOVER = 'rounded-lg shadow-xs border border-zinc-200 dark:border-zinc-600 bg-white dark:bg-zinc-700 p-[.3125rem] focus:outline-hidden';

    /** A row inside a popover list (hover/active). */
    public const OPTION = 'flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-start text-sm text-zinc-800 dark:text-white data-active:bg-zinc-50 dark:data-active:bg-zinc-600 hover:bg-zinc-50 dark:hover:bg-zinc-600';

    /** Dashed drop zone (drag-n-drop, file-drop). */
    public const DROPZONE = 'flex flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed border-zinc-300 dark:border-white/20 bg-zinc-50 dark:bg-white/5 p-6 text-center text-sm text-zinc-500 dark:text-zinc-400';

    /** Muted helper text. */
    public const MUTED = 'text-sm text-zinc-500 dark:text-zinc-400';
}
