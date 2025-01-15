@props(['id', 'placeholder'])

<div {{ $attributes->merge(['class' => 'relative']) }}>
  <input
    class="dark:focus:border-lividus-500 peer h-16 w-full rounded-lg border-2 border-gray-300 bg-transparent p-4 text-gray-900 placeholder-transparent transition duration-150 ease-in focus:border-emerald-500 focus:outline-none dark:border-gray-400 dark:text-gray-50"
    id="{{ $id }}"
    placeholder="{{ $placeholder }}"
  >

  <label
    class="pointer-events-none absolute -top-2 left-4 bg-gray-50 px-2 text-base text-gray-600 transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-lg peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-base peer-focus:text-gray-600 dark:bg-gray-800 dark:text-gray-50 selection:dark:text-gray-50 dark:peer-placeholder-shown:text-gray-400 dark:peer-focus:text-gray-50"
    for="{{ $id }}"
  >
    {{ $placeholder }}
  </label>
</div>
