<?php
// 如：db:seed 或者 清空数据库命令的地方调用
if (!function_exists('insanity_check')) {
    /**
     * 环境检查.
     */
    function insanity_check()
    {
        if (App::environment('production')) {
            dd('别傻了! 这是线上环境。');
        }
    }
}

if (!function_exists('cache_config')) {
    /**
     * 获取缓存key和expire.
     *
     * @param $configName
     *
     * @return array
     */
    function cache_config($configName)
    {
        if (empty($configName) || !is_string($configName)) {
            throw new InvalidArgumentException('无效的参数');
        }

        $config = config('cache-config.'.$configName);
        $config = explode('|', $config);
        $config[1] = (int) $config[1];

        return $config;
    }
}
