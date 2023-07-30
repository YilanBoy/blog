@section('title', '會員中心-修改密碼')

<div
  class="container mx-auto flex-1"
  x-data
  x-init="$refs.currentPassword.focus()"
>
  <div class="flex flex-col items-start justify-center px-4 md:flex-row xl:px-0">
    <livewire:users.edit.side-menu />

    <x-card class="mt-6 flex w-full flex-col justify-center space-y-6 md:mt-0 md:w-1/2">
      {{-- 驗證錯誤訊息 --}}
      <x-auth-validation-errors :errors="$errors" />

      <form
        class="w-full"
        wire:submit.prevent="update"
      >
        {{-- 舊密碼 --}}
        <div>
          <x-floating-label-input
            name="current_password"
            type="password"
            :id="'current_password'"
            :placeholder="'舊密碼'"
            wire:model.defer="current_password"
            required
            x-ref="currentPassword"
          ></x-floating-label-input>
        </div>

        {{-- 新密碼 --}}
        <div class="mt-6">
          <x-floating-label-input
            name="new_password"
            type="password"
            :id="'new_password'"
            :placeholder="'新密碼'"
            wire:model.defer="new_password"
            required
          ></x-floating-label-input>
        </div>

        {{-- 確認新密碼 --}}
        <div class="mt-6">
          <x-floating-label-input
            name="new_password_confirmation"
            type="password"
            :id="'new_password_confirmation'"
            :placeholder="'確認新密碼'"
            wire:model.defer="new_password_confirmation"
            required
          ></x-floating-label-input>
        </div>

        <div class="mt-6 flex items-center justify-end">
          {{-- 儲存按鈕 --}}
          <x-button>
            <i class="bi bi-save2-fill"></i><span class="ml-2">修改密碼</span>
          </x-button>
        </div>
      </form>
    </x-card>
  </div>
</div>
