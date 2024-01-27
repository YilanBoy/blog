<div
  class="bg-gray-50 shadow-lg dark:bg-gray-800 dark:shadow-none lg:hidden"
  x-data="{ menuIsOpen: false }"
>
  <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
    <div class="relative flex h-[4.5rem] items-center justify-between">
      <div class="absolute inset-y-0 left-0 flex items-center">
        {{-- 手機版-開關選單按鈕 --}}
        <button
          class="inline-flex items-center justify-center rounded-md p-2 text-gray-700"
          type="button"
          aria-controls="mobile-menu"
          aria-expanded="false"
          x-on:click="menuIsOpen = ! menuIsOpen"
        >
          <span class="sr-only">Open main menu</span>
          {{-- 手機版-關閉選單的 icon --}}
          <div
            class="text-3xl text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-50"
            :class="menuIsOpen ? 'hidden' : 'block'"
          >
            <x-icon.list class="w-7" />
          </div>
          {{-- 手機版-開啟選單的 icon --}}
          <div
            class="text-xl text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-50"
            x-cloak
            :class="menuIsOpen ? 'block' : 'hidden'"
          >
            <x-icon.x class="w-7" />
          </div>
        </button>
      </div>

      <div class="mx-auto flex items-center">
        <img
          class="hidden size-10 md:inline-block"
          src="{{ asset('images/icon/icon.png') }}"
          alt="logo"
        >
        <span class="ml-3 hidden font-mono text-xl font-bold dark:text-gray-50 md:block">
          {{ config('app.name') }}
        </span>
      </div>

      <div class="absolute inset-y-0 right-0 flex items-center space-x-8">
        {{-- 明亮 / 暗黑模式切換 --}}
        <button
          type="button"
          aria-label="Toggle Dark Mode"
          x-data="{ html: document.documentElement }"
          x-on:click="
            if (html.classList.contains('dark')) {
              html.classList.remove('dark')
              localStorage.setItem('mode', 'light')
            } else {
              html.classList.add('dark')
              localStorage.setItem('mode', 'dark')
            }
          "
        >
          <x-icon.sun class="w-5 text-amber-400 hover:text-amber-500 dark:hidden" />

          <x-icon.moon-stars class="hidden w-5 text-[#f6f1d5] hover:text-[#ddd8bf] dark:inline" />
        </button>

        @guest
          {{-- 手機版-未登入 --}}
          @if ($showRegisterButton)
            <a
              class="rounded-md border-2 border-lividus-400 bg-transparent px-4 py-2 text-lividus-400 hover:border-transparent hover:bg-lividus-400 hover:text-gray-50"
              href="{{ route('register') }}"
              wire:navigate
            >
              註冊
            </a>
          @endif

          <a
            class="rounded-md border-2 border-emerald-600 bg-transparent px-4 py-2 text-emerald-600 hover:border-transparent hover:bg-emerald-600 hover:text-gray-50"
            href="{{ route('login') }}"
            wire:navigate
          >
            登入
          </a>
        @else
          {{-- 手機版-已登入 --}}

          {{-- 手機版-通知 --}}
          <div class="relative inline-flex rounded-md">
            <a
              class="rounded-full text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-50"
              href="{{ route('notifications.index') }}"
              wire:navigate
            >
              <x-icon.bell class="w-5" />
            </a>

            @if (auth()->user()->unreadNotifications->count() > 0)
              <span class="absolute right-2 top-2 -mr-1 -mt-1 flex h-3 w-3">
                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex h-3 w-3 rounded-full bg-red-500"></span>
              </span>
            @endif
          </div>

          {{-- 手機版-會員選單 --}}
          <div
            class="relative"
            x-data="{ showDropdown: false }"
          >
            {{-- 手機版-會員大頭貼 --}}
            <div>
              <button
                class="flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-400"
                id="mobile-user-menu-button"
                type="button"
                aria-expanded="false"
                aria-haspopup="true"
                x-on:click="showDropdown = ! showDropdown"
                x-on:keydown.escape.window="showDropdown = false"
              >
                <span class="sr-only">Open user menu</span>
                <img
                  class="size-10 rounded-full"
                  src="{{ auth()->user()->gravatar_url }}"
                  alt=""
                >
              </button>
            </div>

            {{-- 手機版-會員下拉式選單 --}}
            <div
              class="absolute right-0 mt-2 w-48 rounded-md bg-gray-50 p-2 text-gray-700 shadow-lg ring-1 ring-black ring-opacity-20 dark:bg-gray-800 dark:text-gray-50 dark:shadow-none dark:ring-gray-600"
              role="menu"
              aria-orientation="vertical"
              aria-labelledby="user-menu-button"
              tabindex="-1"
              x-cloak
              x-on:click.outside="showDropdown = false"
              x-show="showDropdown"
              x-transition.origin.top.right
            >
              <a
                class="flex items-center rounded-md px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-700"
                href="{{ route('posts.create') }}"
                role="menuitem"
                tabindex="-1"
                wire:navigate
              >
                <x-icon.pencil class="w-4" />
                <span class="ml-2">新增文章</span>
              </a>

              <a
                class="flex items-center rounded-md px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-700"
                href="{{ route('users.show', ['user' => auth()->id()]) }}"
                role="menuitem"
                tabindex="-1"
                wire:navigate
              >
                <x-icon.info-circle class="w-4" />
                <span class="ml-2">個人資訊</span>
              </a>

              <a
                class="flex items-center rounded-md px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-700"
                href="{{ route('users.edit', ['user' => auth()->id()]) }}"
                role="menuitem"
                tabindex="-1"
                wire:navigate
              >
                <x-icon.person-circle class="w-4" />
                <span class="ml-2">會員中心</span>
              </a>

              <button
                class="flex w-full items-center rounded-md px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-700"
                type="button"
                role="menuitem"
                tabindex="-1"
                wire:confirm="你確定要登出嗎？"
                wire:click="$parent.logout"
              >
                <x-icon.box-arrow-left class="w-4" />
                <span class="ml-2">登出</span>
              </button>
            </div>
          </div>
        @endguest
      </div>
    </div>
  </div>

  {{-- 手機版-分類下拉式選單 --}}
  <div
    class="lg:hidden"
    x-cloak
    x-show="menuIsOpen"
    x-collapse
  >
    <div class="space-y-1 px-2 pb-3 pt-2">

      @php
        $inIndexPage = request()->url() === route('posts.index');
      @endphp

      <a
        href="{{ route('posts.index') }}"
        @if ($inIndexPage) aria-current="page" @endif
        @class([
            'flex items-center px-3 py-2 rounded-md font-medium',
            'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-gray-50' => $inIndexPage,
            'text-gray-500 dark:text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-gray-50' => !$inIndexPage,
        ])
        wire:navigate
      >
        <x-icon.home class="w-4" />
        <span class="ml-2">全部文章</span>
      </a>

      @foreach ($categories as $category)
        @php
          $inCategoryPage = request()->url() === $category->link_with_name;
        @endphp
        <a
          href="{{ $category->link_with_name }}"
          @if ($inCategoryPage) aria-current="page" @endif
          @class([
              'block px-3 py-2 rounded-md font-medium flex items-center',
              'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-gray-50' => $inCategoryPage,
              'text-gray-500 dark:text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-gray-50' => !$inCategoryPage,
          ])
          wire:navigate
        >
          <div class="w-4">{!! $category->icon !!}</div>
          <span class="ml-2">{{ $category->name }}</span>
        </a>
      @endforeach
    </div>
  </div>
</div>
