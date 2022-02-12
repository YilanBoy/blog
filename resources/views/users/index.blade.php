@section('title', $user->name . ' 的個人頁面')

@section('scripts')
  <script src="{{ asset('js/count-up.js') }}"></script>
@endsection

{{-- 個人頁面 --}}
<x-app-layout>
  <div class="container flex-1 mx-auto max-w-7xl">
    <div class="flex flex-col items-center justify-start px-4">

      {{-- 會員資訊、文章與留言 --}}
      <div
        x-data="{
          url: new URL(window.location.href),
          tab: new URLSearchParams(location.search).get('tab') || 'information'
        }"
        class="w-full space-y-6 lg:w-2/3"
      >
        {{-- 切換顯示選單 --}}
        <nav
          class="flex w-full p-1 space-x-1 md:w-4/5 lg:w-1/2 rounded-xl bg-gray-400/30 dark:bg-white/30 dark:text-gray-50">
          <a
            x-on:click.prevent="
              tab = 'information'
              url.searchParams.set('tab', 'information')
              history.pushState(null, document.title, url.toString())
            "
            href="#"
            :class="{
              'bg-gray-50 dark:bg-gray-700': tab === 'information',
              'hover:bg-gray-50 dark:hover:bg-gray-700': tab !== 'information'
            }"
            class="flex justify-center w-1/3 px-4 py-2 transition duration-300 rounded-lg"
          >會員資訊</a>
          <a
            x-on:click.prevent="
              tab = 'posts'
              url.searchParams.set('tab', 'posts')
              history.pushState(null, document.title, url.toString())
            "
            href="#"
            :class="{
              'bg-gray-50 dark:bg-gray-700': tab === 'posts',
              'hover:bg-gray-50 dark:hover:bg-gray-700': tab !== 'posts'
            }"
            class="flex justify-center w-1/3 px-4 py-2 transition duration-300 rounded-lg"
          >發布文章</a>
          <a
            x-on:click.prevent="
              tab = 'comments'
              url.searchParams.set('tab', 'comments')
              history.pushState(null, document.title, url.toString())
            "
            href="#"
            :class="{
              'bg-gray-50 dark:bg-gray-700': tab === 'comments',
              'hover:bg-gray-50 dark:hover:bg-gray-700': tab !== 'comments'
            }"
            class="flex justify-center w-1/3 px-4 py-2 transition duration-300 rounded-lg"
          >留言紀錄</a>
        </nav>

        {{-- 會員資訊 --}}
        <div
          x-cloak
          x-show="tab === 'information'"
          x-transition:enter.duration.300ms
        >
          @include('users.information')
        </div>

        {{-- 會員文章 --}}
        <div
          x-cloak
          x-show="tab === 'posts'"
          x-transition:enter.duration.300ms
        >
          <livewire:user.posts :userId="$user->id"/>
        </div>

        {{-- 會員留言 --}}
        <div
          x-cloak
          x-show="tab === 'comments'"
          x-transition:enter.duration.300ms
        >
          <livewire:user.comments :userId="$user->id"/>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
