<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @namespace Sammy\Packs\YamlLite\Attr\Hooks\Runner
 * - Autoload, application dependencies
 *
 * MIT License
 *
 * Copyright (c) 2020 Ysare
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
namespace Sammy\Packs\YamlLite\Attr\Hooks\Runner {
  use Closure;
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists ('Sammy\Packs\YamlLite\Attr\Hooks\Runner\Base')) {
  /**
   * @trait Base
   * Base internal class for the
   * Runner module.
   * -
   * This is (in the ils environment)
   * an instance of the php module,
   * wich should contain the module
   * core functionalities that should
   * be extended.
   * -
   * For extending the module, just create
   * an 'exts' directory in the module directory
   * and boot it by using the ils directory boot.
   * -
   * \Samils\dir_boot ('./exts');
   */
  trait Base {
    /**
     * @var array YamlLiteHooksBase
     *
     * An array containing whole the declared
     * hooks list.
     * Inside it, should be the declared hooks
     * a key-value pairs sequence. Each function
     * should be the entry point for getting a
     * hook data.
     *
     * Each hook should have multiple handlers,
     * so each of them should be an array containing
     * one or more handlers being an anonymous function
     * wich will be used as the entry point for the hook.
     *
     */
    private static $YamlLiteHooksBase = [];

    public static function addHook ($hookName = null) {
      if (!(is_string ($hookName) && $hookName)) {
        return null;
      }

      $hookHandler = func_get_arg (
        -1 + func_num_args ()
      );

      $hookName = preg_replace ('/\s+/', '',
        strtolower ($hookName)
      );

      $hookNameDeclaredBefore = ( boolean ) (
        isset (self::$YamlLiteHooksBase[ $hookName ]) &&
        is_array (self::$YamlLiteHooksBase[ $hookName ])
      );

      if ( !$hookNameDeclaredBefore ) {
        self::$YamlLiteHooksBase[ $hookName ] = [];
      }

      array_push (self::$YamlLiteHooksBase[ $hookName ],
        $hookHandler
      );
    }

    public final function hook () {
      return call_user_func_array (
        [ static::class, 'addHook' ],
        func_get_args ()
      );
    }

    public static function __callStatic ($methodName, $args) {
      $hookNameRe = '/^run([a-zA-Z0-9_]+)hook$/i';

      if (preg_match ($hookNameRe, $methodName, $match)) {
        $hookName = strtolower ($match [ 1 ]);

        $hookNameDeclaredBefore = ( boolean ) (
          isset (self::$YamlLiteHooksBase[ $hookName ]) &&
          is_array (self::$YamlLiteHooksBase[ $hookName ])
        );

        if ( $hookNameDeclaredBefore ) {
          return call_user_func_array (
            [ static::class, 'handleHook' ],
            [ self::$YamlLiteHooksBase[ $hookName ], $args ]
          );
        }
      }
    }



    protected static final function handleHook ($hookHandlersList, $hookHandlersArguments) {
      if (!(is_array ($hookHandlersList) && $hookHandlersList)) {
        return;
      }

      $finalData = null;

      foreach ($hookHandlersList as $hookHandler) {
        $hookHandler = self::filterHandler ($hookHandler);

        if ( $hookHandler ) {
          return call_user_func_array (
            $hookHandler, $hookHandlersArguments
          );
        }
      }
    }

    protected static final function filterHandler ($hookHandler) {
      if ($hookHandler instanceof Closure) {
        return $hookHandler;
      } else if ( is_array ($hookHandler) && count ($hookHandler) == 2 ) {
        list ($classRefernce, $methodReference) = $hookHandler;

        if (is_object ($classRefernce) && method_exists ($classRefernce, $methodReference)) {
          return $hookHandler;
        } else if (is_string ($classRefernce) && class_exists ($classRefernce)) {
          if (method_exists ($classRefernce, $methodReference)) {
            return $hookHandler;
          }
        }
      }
    }

  }}
}
