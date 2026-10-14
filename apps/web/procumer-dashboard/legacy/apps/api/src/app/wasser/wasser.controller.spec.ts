import {Test, TestingModule} from '@nestjs/testing';
import {WasserController} from './wasser.controller';

describe('WasserController', () => {
  let controller: WasserController;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      controllers: [WasserController]
    }).compile();

    controller = module.get<WasserController>(WasserController);
  });

  it('should be defined', () => {
    expect(controller).toBeDefined();
  });
});
