{{-- user edit side men --}}
<x-card class="flex w-full flex-col items-center justify-center dark:text-gray-50 md:mr-6 md:w-60 xl:w-80">
  <h3 class="flex w-full items-center justify-center text-center text-lg font-semibold">
    <x-icon.person-circle class="w-6" />
    <span class="ml-2">會員中心</span>
  </h3>

  <hr class="my-4 h-0.5 w-full border-0 bg-gray-300 dark:bg-gray-700">

  <div class="flex w-full flex-col space-y-1">
    @php
      $inEditUserPage = request()->routeIs('users.edit');
    @endphp
    <a
      href="{{ route('users.edit', ['id' => auth()->id()]) }}"
      @class([
          'flex items-center rounded-md p-2 dark:text-gray-50',
          'bg-gray-200 dark:bg-gray-700' => $inEditUserPage,
          'hover:bg-gray-200 dark:hover:bg-gray-700' => !$inEditUserPage,
      ])
      wire:navigate
    >
      <x-icon.person-lines class="w-5" />
      <span class="ml-2">編輯個人資料</span>
    </a>

    @php
      $inChangePasswordPage = request()->routeIs('users.updatePassword');
    @endphp
    <a
      href="{{ route('users.updatePassword', ['id' => auth()->id()]) }}"
      @class([
          'flex items-center rounded-md p-2 dark:text-gray-50',
          'bg-gray-200 dark:bg-gray-700' => $inChangePasswordPage,
          'hover:bg-gray-200 dark:hover:bg-gray-700' => !$inChangePasswordPage,
      ])
      wire:navigate
    >
      <x-icon.file-earmark-lock class="w-5" />
      <span class="ml-2">修改密碼</span>
    </a>

    @php
      $inDestroyUserPage = request()->routeIs('users.destroy');
    @endphp
    <a
      href="{{ route('users.destroy', ['id' => auth()->id()]) }}"
      @class([
          'flex items-center rounded-md p-2 dark:text-gray-50',
          'bg-gray-200 dark:bg-gray-700' => $inDestroyUserPage,
          'hover:bg-gray-200 dark:hover:bg-gray-700' => !$inDestroyUserPage,
      ])
      wire:navigate
    >
      <x-icon.person-x class="w-5" />
      <span class="ml-2">刪除帳號</span>
    </a>
  </div>
</x-card>
