import {Logger} from '@nestjs/common';
import * as cacheManager from 'cache-manager';
import {of} from 'rxjs';

const memoryCache = cacheManager.caching({store: 'memory', max: 100, ttl: 10});

/**
 * Caches the return value of the decorated function using the given key for the given time-to-live
 * @param {string} key
 * @param {number | Function} ttl
 * @constructor
 */
// eslint-disable-next-line @typescript-eslint/ban-types
export function Cache({key, ttl}: {key?: string; ttl: number | Function}): any {
  // Logger.log(`Cache decorator initialized for key '${key}'`, 'InstanceLoader');

  return function (target: Record<string, any>, propertyKey: string, descriptor: PropertyDescriptor) {
    if (!key) {
      key = `${target.constructor.name}/${propertyKey.toString()}`;
    }

    // get the original method which is decorated by this decorator
    const method = descriptor.value;

    // execute this method instead
    descriptor.value = async function (...args: Array<any>) {
      // check if the cache contains a value for the given key
      const cachedItem = await memoryCache.get(key);
      if (cachedItem) {
        Logger.log(`Returning cached item for key '${key}'`, Cache.name);
        // Logger.debug(`Returning cached item for key '${key}': ${JSON.stringify(cachedItem)}`, Cache.name);
        return of(cachedItem);
      }

      // execute the original method
      const result = await method.apply(this, args);

      // cache the result
      const calcTtl = typeof ttl === 'function' ? ttl() : ttl;
      Logger.log(`Create a new cache entry for key '${key}'`, Cache.name);
      // Logger.debug(
      //   `Create a new cache entry for key '${key}' for the duration of ${calcTtl} seconds: ${JSON.stringify(result)}`,
      //   Cache.name
      // );
      await memoryCache.set(key, result, {ttl: calcTtl});

      return result;
    };
  };
}
