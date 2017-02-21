<?php

namespace Phambinh\Page;

use Phambinh\Laravel\Database\Traits\Query;
use Phambinh\Laravel\Database\Traits\Metable;
use Phambinh\Laravel\Database\Traits\Model as PhambinhModel;
use Illuminate\Database\Eloquent\Model;

class Page extends Model implements Query
{
    use PhambinhModel;
    
    protected $table = 'pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'sub_content',
        'author_id',
        'status',
        'thumbnail',
        'created_at',
        'meta_title',
        'meta_description',
        'meta_keyword',
    ];

     /**
     * Các tham số được phép truyền vào từ URL
     *
     * @var array
     */
    protected static $requestFilter = [
        'id' => '',
        'title' => '',
        'status' => 'in:pending,enable,disable',
        'time_status' => 'in:coming,enable,disable',
        'orderby' => '',
        '_keyword' => '',
    ];

    /**
     * Giá trị mặc định của các tham số
     *
     * @var array
     */
    protected static $defaultOfQuery = [
        'status'        => 'enable',
        'orderby'        =>    'created_at.desc',
    ];

    protected static $statusAble = [
        ['slug' => 'disable', 'name' => 'Xóa tạm'],
        ['slug' => 'enable', 'name' => 'Công khai'],
    ];

    protected static $searchFields = [
        'news.id',
        'news.title',
    ];

    public function author()
    {
        return $this->beLongsTo('Phambinh\Cms\User');
    }

    public function scopeOfQuery($query, $args = [])
    {
        $query->baseQuery($args);

        if (! empty($args['status'])) {
            switch ($args['status']) {
                case 'enable':
                    $query->enable();
                    break;
                
                case 'disable':
                    $query->disable();
                    break;
            }
        }

        if (! empty($args['_keyword'])) {
            $query->querySearch($args['_keyword']);
        }

        if (! empty($args['author_id'])) {
            $query->where('author_id', $args['author_id']);
        }

        if (! empty($args['title'])) {
            $query->where('title', $args['title']);
        }
    }

    public function isEnable()
    {
        $statusCode = $this->status;
        return $statusCode == '1';
    }

    public function isDisable()
    {
        $statusCode = $this->status;
        return $statusCode == '0';
    }

    public function statusHtmlClass()
    {
        if ($this->status == '0') {
            return 'bg-danger';
        }

        return null;
    }

    public function thumbnailOrDefault()
    {
        if (! empty($this->thumbnail)) {
            return $this->thumbnail;
        }

        return setting('default-thumbnail');
    }

    public static function statusAble()
    {
        return self::$statusAble;
    }

    public function scopeEnable($query)
    {
        return $query->where('status', '1');
    }

    public function scopeSearch($query, $keyword)
    {
        $keyword = str_keyword($keyword);
        foreach (self::$searchFields as $index => $field) {
            if ($index == 0) {
                $query->where($field, 'like', $keyword);
            } else {
                $query->orWhere($field, 'like', $keyword);
            }
        }
    }

    public function scopeDisable($query)
    {
        return $query->where('status', '0');
    }

    public function scopePending($query)
    {
        return $query->where('status', '3');
    }

    public function markAsEnable()
    {
        $this->where('id', $this->id)->update(['status' => '1']);
    }

    public function markAsDisable()
    {
        $this->where('id', $this->id)->update(['status' => '0']);
    }
}
