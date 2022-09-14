<?php

namespace App\Services;

class FormatTransferService
{
    /**
     * 將 tag 的 JSON 資料轉成 array
     *
     * @param  string|null  $tagsJson
     * @return array
     */
    public static function tagsJsonToTagIdsArray(?string $tagsJson): array
    {
        // 沒有設定標籤
        if (is_null($tagsJson)) {
            return [];
        }

        $tags = json_decode($tagsJson);

        // 生成由 tag ID 組成的 Array
        return collect($tags)
            ->map(fn ($tag) => $tag->id)
            ->all();
    }
}
